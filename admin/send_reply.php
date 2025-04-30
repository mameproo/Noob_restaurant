<?php
require_once '../config.php';
require_once 'includes/auth.php';

// Check if user is logged in and is admin
if (!is_logged_in() || !is_admin()) {
    redirect_with_message('login.php', 'Please login to access the admin panel.', 'warning');
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback_id = $_POST['feedback_id'] ?? 0;
    $customer_email = $_POST['customer_email'] ?? '';
    $customer_name = $_POST['customer_name'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    // Validate inputs
    if (empty($customer_email) || empty($subject) || empty($message)) {
        redirect_with_message('feedback.php', 'Please fill in all required fields.', 'error');
    }

    // Get site settings for email configuration
    $site_settings = get_site_settings();
    $site_name = $site_settings['site_name'] ?? 'Noob Restaurant';
    $admin_email = $site_settings['admin_email'] ?? '';

    // Prepare email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . $site_name . " <" . $admin_email . ">" . "\r\n";
    $headers .= "Reply-To: " . $admin_email . "\r\n";

    // Prepare email body
    $email_body = "
    <html>
    <head>
        <title>" . htmlspecialchars($subject) . "</title>
    </head>
    <body>
        <p>Dear " . htmlspecialchars($customer_name) . ",</p>
        <p>" . nl2br(htmlspecialchars($message)) . "</p>
        <p>Best regards,<br>" . htmlspecialchars($site_name) . " Team</p>
    </body>
    </html>";

    // Send email
    $mail_sent = mail($customer_email, $subject, $email_body, $headers);

    if ($mail_sent) {
        // Update feedback status to read
        $sql = "UPDATE feedback SET is_read = 1 WHERE id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $feedback_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // Log the email reply
        log_admin_action("Replied to feedback #" . $feedback_id);

        redirect_with_message('feedback.php', 'Reply sent successfully.', 'success');
    } else {
        redirect_with_message('feedback.php', 'Failed to send reply. Please try again.', 'error');
    }
} else {
    redirect_with_message('feedback.php', 'Invalid request method.', 'error');
} 