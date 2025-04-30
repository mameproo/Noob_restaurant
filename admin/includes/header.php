<?php
require_once '../config.php';
require_once 'auth.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Get current page name for active state
$current_page = basename($_SERVER['PHP_SELF']);

$unread_feedback = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM feedback WHERE is_read = 0"))['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Noob Restaurant</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            background: #343a40;
            color: #fff;
            transition: all 0.3s;
        }
        #sidebar.active {
            margin-left: -250px;
        }
        #sidebar .sidebar-header {
            padding: 20px;
            background: #2c3136;
        }
        #sidebar ul.components {
            padding: 20px 0;
        }
        #sidebar ul li a {
            padding: 10px 20px;
            font-size: 1.1em;
            display: block;
            color: #fff;
            text-decoration: none;
        }
        #sidebar ul li a:hover {
            background: #2c3136;
        }
        #sidebar ul li.active > a {
            background: #0d6efd;
        }
        #content {
            width: 100%;
            padding: 20px;
            min-height: 100vh;
            transition: all 0.3s;
        }
        .navbar {
            padding: 15px 10px;
            background: #fff;
            border: none;
            border-radius: 0;
            margin-bottom: 20px;
            box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        }
        .badge {
            margin-left: 5px;
        }
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
            }
            #sidebar.active {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Noob Restaurant</h3>
                <p class="text-muted mb-0">Admin Panel</p>
            </div>

            <ul class="list-unstyled components">
                <li class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                    <a href="index.php">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                </li>
                <li class="<?php echo $current_page == 'menu.php' ? 'active' : ''; ?>">
                    <a href="menu.php">
                        <i class="fas fa-utensils me-2"></i>Menu Management
                    </a>
                </li>
                <li class="<?php echo $current_page == 'categories.php' ? 'active' : ''; ?>">
                    <a href="categories.php">
                        <i class="fas fa-list me-2"></i>Categories
                    </a>
                </li>
                <li class="<?php echo $current_page == 'testimonials.php' ? 'active' : ''; ?>">
                    <a href="testimonials.php">
                        <i class="fas fa-comments me-2"></i>Testimonials
                    </a>
                </li>
                <li class="<?php echo $current_page == 'feedback.php' ? 'active' : ''; ?>">
                    <a href="feedback.php">
                        <i class="fas fa-envelope me-2"></i>Feedback
                        <?php if ($unread_feedback > 0): ?>
                            <span class="badge bg-danger"><?php echo $unread_feedback; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="<?php echo $current_page == 'settings.php' ? 'active' : ''; ?>">
                    <a href="settings.php">
                        <i class="fas fa-cog me-2"></i>Settings
                    </a>
                </li>
                <li class="mt-4">
                    <a href="logout.php">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-dark">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>" href="index.php">
                                    <i class="fas fa-home me-2"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'menu.php' ? 'active' : ''; ?>" href="menu.php">
                                    <i class="fas fa-utensils me-2"></i>Menu Management
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'categories.php' ? 'active' : ''; ?>" href="categories.php">
                                    <i class="fas fa-list me-2"></i>Categories
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'testimonials.php' ? 'active' : ''; ?>" href="testimonials.php">
                                    <i class="fas fa-comments me-2"></i>Testimonials
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'feedback.php' ? 'active' : ''; ?>" href="feedback.php">
                                    <i class="fas fa-envelope me-2"></i>Feedback
                                    <?php if ($unread_feedback > 0): ?>
                                        <span class="badge bg-danger"><?php echo $unread_feedback; ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $current_page == 'settings.php' ? 'active' : ''; ?>" href="settings.php">
                                    <i class="fas fa-cog me-2"></i>Settings
                                </a>
                            </li>
                        </ul>
                        <div class="d-flex align-items-center">
                            <a href="../index.php" class="btn btn-outline-primary me-2" target="_blank">
                                <i class="fas fa-external-link-alt me-1"></i>View Site
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                                    <i class="fas fa-user me-1"></i>Admin
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="settings.php">
                                        <i class="fas fa-cog me-2"></i>Settings
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="logout.php">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="container-fluid">
                <?php
                // Display flash messages
                if (isset($_SESSION['flash_message'])) {
                    echo '<div class="alert alert-' . $_SESSION['flash_type'] . ' alert-dismissible fade show" role="alert">';
                    echo $_SESSION['flash_message'];
                    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '</div>';
                    unset($_SESSION['flash_message']);
                    unset($_SESSION['flash_type']);
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById("sidebar");
            const content = document.getElementById("content");
            const sidebarCollapse = document.getElementById("sidebarCollapse");

            sidebarCollapse.addEventListener("click", function() {
                sidebar.classList.toggle("active");
                content.classList.toggle("active");
            });
        });
    </script>
</body>
</html> 