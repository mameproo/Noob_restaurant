<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'noob_restaurant');

// Create database connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to utf8mb4
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Sorry, there was a problem connecting to the database. Please try again later.");
}

// Site settings
define('SITE_NAME', 'Noob Restaurant');
define('SITE_EMAIL', 'info@noobrestaurant.com');
define('SITE_PHONE', '+251 911 234 567');
define('SITE_ADDRESS', 'Bole Road, Addis Ababa, Ethiopia');

// Session timeout (in seconds)
define('SESSION_TIMEOUT', 1800); // 30 minutes

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Function to check if user is admin
function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Function to redirect with message
function redirect_with_message($url, $message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    header("Location: $url");
    exit();
}

// Simple redirect function
function redirect($url) {
    header("Location: $url");
    exit();
}

// Function to get site settings
function get_setting($key) {
    global $conn;
    $key = sanitize_input($key);
    $stmt = $conn->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
    $stmt->bind_param("s", $key);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['setting_value'];
    }
    return null;
}

// Function to update site setting
function update_setting($key, $value) {
    global $conn;
    $key = sanitize_input($key);
    $value = sanitize_input($value);
    $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
    $stmt->bind_param("ss", $value, $key);
    return $stmt->execute();
}

// Function to get menu categories
function get_menu_categories() {
    global $conn;
    $result = $conn->query("SELECT * FROM menu_categories ORDER BY name");
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    return $categories;
}

// Function to get menu items by category
function get_menu_items($category_id = null) {
    global $conn;
    $sql = "SELECT * FROM menu_items WHERE is_available = 1";
    if ($category_id) {
        $sql .= " AND category_id = " . (int)$category_id;
    }
    $sql .= " ORDER BY name";
    $result = $conn->query($sql);
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    return $items;
}

// Function to get approved testimonials
function get_approved_testimonials() {
    global $conn;
    $result = $conn->query("SELECT * FROM testimonials WHERE is_approved = 1 ORDER BY created_at DESC");
    $testimonials = [];
    while ($row = $result->fetch_assoc()) {
        $testimonials[] = $row;
    }
    return $testimonials;
}

// Flash message function
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

// CSRF Protection
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

// Enhanced input validation
function validateInput($data, $type = 'string', $options = []) {
    $data = trim($data);
    $data = stripslashes($data);
    
    switch ($type) {
        case 'email':
            return filter_var($data, FILTER_VALIDATE_EMAIL) ? $data : false;
            
        case 'int':
            return filter_var($data, FILTER_VALIDATE_INT) ? (int)$data : false;
            
        case 'float':
            return filter_var($data, FILTER_VALIDATE_FLOAT) ? (float)$data : false;
            
        case 'url':
            return filter_var($data, FILTER_VALIDATE_URL) ? $data : false;
            
        case 'alpha':
            return preg_match('/^[a-zA-Z]+$/', $data) ? $data : false;
            
        case 'alphanum':
            return preg_match('/^[a-zA-Z0-9]+$/', $data) ? $data : false;
            
        case 'phone':
            return preg_match('/^[0-9+\-\s()]{10,20}$/', $data) ? $data : false;
            
        case 'date':
            $format = isset($options['format']) ? $options['format'] : 'Y-m-d';
            $date = DateTime::createFromFormat($format, $data);
            return $date && $date->format($format) === $data ? $data : false;
            
        case 'enum':
            return in_array($data, $options['values']) ? $data : false;
            
        default:
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}

// Function to get all site settings
function get_site_settings() {
    global $conn;
    $settings = [];
    
    // Get settings from database
    $stmt = $conn->prepare("SELECT setting_key, setting_value FROM settings");
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    
    // Add default settings if not found in database
    $settings['site_name'] = $settings['site_name'] ?? SITE_NAME;
    $settings['admin_email'] = $settings['admin_email'] ?? SITE_EMAIL;
    $settings['site_phone'] = $settings['site_phone'] ?? SITE_PHONE;
    $settings['site_address'] = $settings['site_address'] ?? SITE_ADDRESS;
    
    return $settings;
}
?> 