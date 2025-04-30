<?php
require_once 'config.php';

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize input
    $name = sanitize_input($_POST['name'] ?? '');
    $comment = sanitize_input($_POST['comment'] ?? '');
    $rating = (int)($_POST['rating'] ?? 0);

    // Validate input
    $errors = [];
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    if (empty($comment)) {
        $errors[] = "Comment is required";
    }
    if ($rating < 1 || $rating > 5) {
        $errors[] = "Rating must be between 1 and 5";
    }

    // If no errors, insert into database
    if (empty($errors)) {
        $sql = "INSERT INTO testimonials (name, comment, rating) VALUES (?, ?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssi", $name, $comment, $rating);
            if (mysqli_stmt_execute($stmt)) {
                // Success - redirect back with success message
                $_SESSION['flash_message'] = "Thank you for your testimonial! It will be reviewed by our team.";
                $_SESSION['flash_type'] = "success";
            } else {
                $_SESSION['flash_message'] = "Error submitting testimonial. Please try again.";
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

// Redirect back to the page with the form
header("Location: " . $_SERVER['HTTP_REFERER'] . "#testimonial-form");
exit(); 