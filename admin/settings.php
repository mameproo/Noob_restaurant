<?php
require_once '../config.php';

// Check if user is logged in and is admin
if (!is_logged_in() || !is_admin()) {
    redirect_with_message('login.php', 'Please login to access the admin panel.', 'warning');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['settings'] as $key => $value) {
        $key = sanitize_input($key);
        $value = sanitize_input($value);
        
        $sql = "UPDATE settings SET setting_value = ? WHERE setting_key = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $value, $key);
            if (mysqli_stmt_execute($stmt)) {
                setFlashMessage('success', 'Settings updated successfully.');
            } else {
                setFlashMessage('error', 'Error updating settings.');
            }
            mysqli_stmt_close($stmt);
        }
    }
    redirect_with_message('settings.php', 'Settings updated successfully.', 'success');
}

// Get all settings
$settings = [];
$result = mysqli_query($conn, "SELECT * FROM settings ORDER BY setting_key");
while ($row = mysqli_fetch_assoc($result)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Settings - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Include TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '.tinymce',
            height: 300,
            plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }'
        });
    </script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
           <!-- Sidebar -->
           <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white"><?php echo SITE_NAME; ?></h4>
                        <p class="text-muted">Admin Panel</p>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="index.php">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="menu.php">
                                <i class="fas fa-utensils me-2"></i>
                                Menu Items
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="categories.php">
                                <i class="fas fa-list me-2"></i>
                                Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="testimonials.php">
                                <i class="fas fa-comments me-2"></i>
                                Testimonials
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="feedback.php">
                                <i class="fas fa-envelope me-2"></i>
                                Feedback
                            </a>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="settings.php">
                                <i class="fas fa-cog me-2"></i>
                                Settings
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <a class="nav-link text-white" href="logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Site Settings</h1>
                </div>

                <?php
                $flash = getFlashMessage();
                if ($flash): ?>
                    <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show" role="alert">
                        <?php echo $flash['message']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">General Settings</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="site_name" class="form-label">Site Name</label>
                                    <input type="text" name="settings[site_name]" id="site_name" class="form-control" 
                                           value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="site_email" class="form-label">Site Email</label>
                                    <input type="email" name="settings[site_email]" id="site_email" class="form-control" 
                                           value="<?php echo htmlspecialchars($settings['site_email'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="site_phone" class="form-label">Site Phone</label>
                                    <input type="text" name="settings[site_phone]" id="site_phone" class="form-control" 
                                           value="<?php echo htmlspecialchars($settings['site_phone'] ?? ''); ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="site_address" class="form-label">Site Address</label>
                                    <input type="text" name="settings[site_address]" id="site_address" class="form-control" 
                                           value="<?php echo htmlspecialchars($settings['site_address'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="facebook_url" class="form-label">Facebook URL</label>
                                    <input type="url" name="settings[facebook_url]" id="facebook_url" class="form-control" 
                                           value="<?php echo htmlspecialchars($settings['facebook_url'] ?? ''); ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="twitter_url" class="form-label">Twitter URL</label>
                                    <input type="url" name="settings[twitter_url]" id="twitter_url" class="form-control" 
                                           value="<?php echo htmlspecialchars($settings['twitter_url'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="instagram_url" class="form-label">Instagram URL</label>
                                    <input type="url" name="settings[instagram_url]" id="instagram_url" class="form-control" 
                                           value="<?php echo htmlspecialchars($settings['instagram_url'] ?? ''); ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="youtube_url" class="form-label">YouTube URL</label>
                                    <input type="url" name="settings[youtube_url]" id="youtube_url" class="form-control" 
                                           value="<?php echo htmlspecialchars($settings['youtube_url'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html> 