<?php
require_once '../config.php';
require_once 'includes/auth.php';

// Check if user is logged in and is admin
if (!is_logged_in() || !is_admin()) {
    redirect('../index.php');
}

// Initialize variables
$name = '';
$name_am = '';
$description = '';
$icon = '';
$error = '';
$success = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $name = sanitize_input($_POST['name']);
    $name_am = sanitize_input($_POST['name_am']);
    $description = sanitize_input($_POST['description']);
    $icon = sanitize_input($_POST['icon']);
    
    // Validate input
    if (empty($name)) {
        $error = 'Category name is required';
    } else {
        // Check if category already exists
        $check_query = "SELECT * FROM menu_categories WHERE name = '$name'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = 'Category already exists';
        } else {
            // Insert category into database
            $query = "INSERT INTO menu_categories (name, name_am, description, icon) 
                      VALUES ('$name', '$name_am', '$description', '$icon')";
            
            if (mysqli_query($conn, $query)) {
                // Log admin action
                log_admin_action('Added new menu category: ' . $name);
                
                // Set success message and redirect
                setFlashMessage('success', 'Category added successfully');
                redirect('categories.php');
            } else {
                $error = 'Database error: ' . mysqli_error($conn);
            }
        }
    }
}

// If we get here, there was an error
setFlashMessage('error', $error);
redirect('categories.php');
?> 