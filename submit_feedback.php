<?php
require_once 'config.php';

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize input
    $name = sanitize_input($_POST['name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $subject = sanitize_input($_POST['subject'] ?? '');
    $message = sanitize_input($_POST['message'] ?? '');

    // Validate input
    $errors = [];
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    if (empty($subject)) {
        $errors[] = "Subject is required";
    }
    if (empty($message)) {
        $errors[] = "Message is required";
    }

    // If no errors, insert into database
    if (empty($errors)) {
        // Create feedback table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS feedback (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            subject VARCHAR(200) NOT NULL,
            message TEXT NOT NULL,
            is_read BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        mysqli_query($conn, $sql);

        // Insert the feedback
        $sql = "INSERT INTO feedback (name, email, subject, message) VALUES (?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);
            if (mysqli_stmt_execute($stmt)) {
                // Success - redirect back with success message
                $_SESSION['flash_message'] = "Thank you for your feedback! We will get back to you soon.";
                $_SESSION['flash_type'] = "success";
            } else {
                $_SESSION['flash_message'] = "Error submitting feedback. Please try again.";
                $_SESSION['flash_type'] = "error";
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        // Store errors in session
        $_SESSION['flash_message'] = implode("<br>", $errors);
        $_SESSION['flash_type'] = "error";
    }
}

// Redirect back to the contact page
header("Location: contact.php");
exit(); 