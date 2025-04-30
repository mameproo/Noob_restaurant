<?php
require_once '../config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if already logged in
if (is_logged_in() && is_admin()) {
    redirect_with_message('index.php', 'You are already logged in.', 'info');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];
    
    // Debug information
    error_log("Login attempt - Username: $username");
    
    $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $role);
                if (mysqli_stmt_fetch($stmt)) {
                    error_log("User found - Role: $role");
                    
                    // Verify password
                    if (password_verify($password, $hashed_password)) {
                        error_log("Password verified successfully");
                        
                        if ($role == 'admin') {
                            // Set session variables
                            $_SESSION['user_id'] = $id;
                            $_SESSION['username'] = $username;
                            $_SESSION['user_role'] = $role;
                            
                            error_log("Login successful - Redirecting to admin panel");
                            redirect_with_message('index.php', 'Login successful!', 'success');
                        } else {
                            $error = "You do not have admin privileges.";
                            error_log("Login failed - User is not an admin");
                        }
                    } else {
                        $error = "Invalid username or password.";
                        error_log("Login failed - Invalid password");
                    }
                }
            } else {
                $error = "Invalid username or password.";
                error_log("Login failed - User not found");
            }
        } else {
            $error = "Oops! Something went wrong. Please try again later.";
            error_log("Database error: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Admin Login</h3>
                        
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html> 