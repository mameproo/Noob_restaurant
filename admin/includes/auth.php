<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 * 
 * @return bool Whether the user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if user is an admin
 * 
 * @return bool Whether the user is an admin
 */
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Log an admin action
 * 
 * @param string $action The action performed
 * @param string $details Additional details about the action
 */
function log_admin_action($action, $details = '') {
    global $conn;
    if (isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        $ip_address = $_SERVER['REMOTE_ADDR'];
        
        // Check if system_logs table exists
        $table_exists = mysqli_query($conn, "SHOW TABLES LIKE 'system_logs'");
        if (mysqli_num_rows($table_exists) == 0) {
            // Create the table if it doesn't exist
            $sql = "CREATE TABLE IF NOT EXISTS system_logs (
                id INT(11) NOT NULL AUTO_INCREMENT,
                user_id INT(11) NOT NULL,
                action VARCHAR(255) NOT NULL,
                details TEXT,
                ip_address VARCHAR(45) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY user_id (user_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            mysqli_query($conn, $sql);
        }
        
        // Now insert the log entry
        $sql = "INSERT INTO system_logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "isss", $user_id, $action, $details, $ip_address);
        mysqli_stmt_execute($stmt);
    }
} 