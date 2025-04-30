<?php
require_once 'config.php';

// Get site settings
$settings = [];
$result = mysqli_query($conn, "SELECT * FROM settings");
while ($row = mysqli_fetch_assoc($result)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Get menu categories
$categories = mysqli_query($conn, "SELECT * FROM menu_categories ORDER BY name");

// Get menu items by category
$menu_items = [];
while ($category = mysqli_fetch_assoc($categories)) {
    $category_id = $category['id'];
    $menu_items[$category_id] = mysqli_query($conn, "SELECT * FROM menu_items WHERE category_id = $category_id AND is_available = 1 ORDER BY name");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Menu - <?php echo htmlspecialchars($settings['site_name']); ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 px-lg-5 py-3 py-lg-0">
            <a href="" class="navbar-brand p-0">
                <h1 class="text-primary m-0"><i class="fa fa-utensils me-3"></i><?php echo htmlspecialchars($settings['site_name']); ?></h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0 pe-4">
                    <a href="index.php" class="nav-item nav-link">Home</a>
                    <a href="menu.php" class="nav-item nav-link active">Menu</a>
                    <a href="about.php" class="nav-item nav-link">About</a>
                    <a href="service.php" class="nav-item nav-link">Service</a>
                    <a href="contact.php" class="nav-item nav-link">Contact</a>
                </div>
            </div>
        </nav>
        <!-- Navbar End -->

        <!-- Page Header Start -->
        <div class="container-xxl py-5 bg-dark hero-header mb-5">
            <div class="container text-center my-5 pt-5 pb-4">
                <h1 class="display-3 text-white mb-3 animated slideInDown">Our Menu</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb text-uppercase mb-0">
                        <li class="breadcrumb-item"><a class="text-white" href="index.php">Home</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page">Menu</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Menu Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h5 class="section-title ff-secondary text-center text-primary fw-normal">Food Menu</h5>
                    <h1 class="mb-5">Most Popular Items</h1>
                </div>
                <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.1s">
                    <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
                        <?php 
                        mysqli_data_seek($categories, 0);
                        $first = true;
                        while ($category = mysqli_fetch_assoc($categories)): 
                        ?>
                            <li class="nav-item">
                                <a class="d-flex align-items-center text-start mx-3 <?php echo $first ? 'ms-0' : ''; ?> pb-3 <?php echo $first ? 'active' : ''; ?>" 
                                   data-bs-toggle="pill" 
                                   href="#tab-<?php echo $category['id']; ?>">
                                    <i class="fa <?php echo htmlspecialchars($category['icon']); ?> fa-2x text-primary"></i>
                                    <div class="ps-3">
                                        <small class="text-body">Food</small>
                                        <h6 class="mt-n1 mb-0"><?php echo htmlspecialchars($category['name_am']); ?></h6>
                                    </div>
                                </a>
                            </li>
                        <?php 
                        $first = false;
                        endwhile; 
                        ?>
                    </ul>

                    <div class="tab-content">
                        <?php 
                        mysqli_data_seek($categories, 0);
                        $first = true;
                        while ($category = mysqli_fetch_assoc($categories)): 
                        ?>
                            <div id="tab-<?php echo $category['id']; ?>" class="tab-pane fade show <?php echo $first ? 'active' : ''; ?> p-0">
                                <div class="row g-4">
                                    <?php while ($item = mysqli_fetch_assoc($menu_items[$category['id']])): ?>
                                        <div class="col-lg-8">
                                            <div class="d-flex align-items-center">
                                                <img class="flex-shrink-0 img-fluid rounded" 
                                                     src="<?php echo $item['image'] ? 'img/menu/' . htmlspecialchars($item['image']) : 'https://via.placeholder.com/200x200'; ?>" 
                                                     alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                                     style="width: 200px; height: 200px; object-fit: cover;">
                                                <div class="w-100 d-flex flex-column text-start ps-4">
                                                    <h5 class="d-flex justify-content-between border-bottom pb-2">
                                                        <span><?php echo htmlspecialchars($item['name_am']); ?></span>
                                                        <span class="text-primary"><?php echo number_format($item['price'], 2); ?> ETB</span>
                                                    </h5>
                                                    <small class="fst-italic"><?php echo htmlspecialchars($item['description_am']); ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        <?php 
                        $first = false;
                        endwhile; 
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Menu End -->

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-light footer pt-5 mt-5">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Contact</h4>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i><?php echo htmlspecialchars($settings['site_address']); ?></p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i><?php echo htmlspecialchars($settings['site_phone']); ?></p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i><?php echo htmlspecialchars($settings['site_email']); ?></p>
                        <div class="col-12 pt-4">
                            <div class="d-flex">
                                <?php if ($settings['facebook_url']): ?>
                                    <a class="btn btn-square btn-outline-light me-1" href="<?php echo htmlspecialchars($settings['facebook_url']); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                <?php endif; ?>
                                <?php if ($settings['twitter_url']): ?>
                                    <a class="btn btn-square btn-outline-light me-1" href="<?php echo htmlspecialchars($settings['twitter_url']); ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                                <?php endif; ?>
                                <?php if ($settings['instagram_url']): ?>
                                    <a class="btn btn-square btn-outline-light me-1" href="<?php echo htmlspecialchars($settings['instagram_url']); ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                                <?php endif; ?>
                                <?php if ($settings['youtube_url']): ?>
                                    <a class="btn btn-square btn-outline-light me-1" href="<?php echo htmlspecialchars($settings['youtube_url']); ?>" target="_blank"><i class="fab fa-youtube"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Opening</h4>
                        <h5 class="text-light fw-normal"><?php echo htmlspecialchars($settings['opening_hours']); ?></h5>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#"><?php echo htmlspecialchars($settings['site_name']); ?></a>, All Rights Reserved. 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/daterangepicker.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html> 