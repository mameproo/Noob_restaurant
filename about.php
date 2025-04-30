<?php
require_once 'config.php';

// Get site settings
$settings = [];
$result = mysqli_query($conn, "SELECT * FROM settings");
while ($row = mysqli_fetch_assoc($result)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>About Us - <?php echo htmlspecialchars($settings['site_name'] ?? 'Noob Restaurant'); ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="assets/img/favicon.ico" rel="icon">

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


            <!-- Navbar & Hero Start -->
            <div class="container-xxl position-relative p-0">
                <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 px-lg-5 py-3 py-lg-0">
                    <a href="" class="navbar-brand p-0">
                        <h1 class="text-primary m-0"><i class="fa fa-utensils me-3"></i><?php echo htmlspecialchars($settings['site_name'] ?? 'Noob Restaurant'); ?></h1>
                        <!-- <img src="assets/img/logo.png" alt="Logo"> -->
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        <div class="navbar-nav ms-auto py-0 pe-4">
                            <a href="index.php" class="nav-item nav-link">Home</a>
                            <a href="menu.php" class="nav-item nav-link">Menu</a>
                            <a href="about.php" class="nav-item nav-link active">About</a>
                            <a href="service.php" class="nav-item nav-link">Service</a>
                            <a href="contact.php" class="nav-item nav-link">Contact</a>
                            </div>
                        </div>
                    </div>
                </nav>

            <div class="container-xxl py-5 bg-dark hero-header mb-5">
                <div class="container text-center my-5 pt-5 pb-4">
                    <h1 class="display-3 text-white mb-3 animated slideInDown">About Us</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center text-uppercase">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">About</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Navbar & Hero End -->

        <!-- About Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="row g-3">
                            <div class="col-6 text-start">
                                <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.1s" src="assets/img/about-1.jpg" alt="Restaurant Interior">
                            </div>
                            <div class="col-6 text-start">
                                <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.3s" src="assets/img/about-2.jpg" alt="Our Chef" style="margin-top: 25%;">
                            </div>
                            <div class="col-6 text-end">
                                <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.5s" src="assets/img/about-3.jpg" alt="Our Food">
                            </div>
                            <div class="col-6 text-end">
                                <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.7s" src="assets/img/about-4.jpg" alt="Our Staff">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="section-title ff-secondary text-start text-primary fw-normal">About Us</h5>
                        <h1 class="mb-4">Welcome to <i class="fa fa-utensils text-primary me-2"></i><?php echo htmlspecialchars($settings['site_name'] ?? 'Noob Restaurant'); ?></h1>
                        <p class="mb-4"><?php echo $settings['about_text'] ?? 'We are a family-owned restaurant dedicated to serving the best food in town. Our chefs use only the freshest ingredients to create delicious meals that will satisfy your taste buds.'; ?></p>
                        <div class="row g-4 mb-4">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center border-start border-5 border-primary px-3">
                                    <h1 class="flex-shrink-0 display-5 text-primary mb-0" data-toggle="counter-up"><?php echo htmlspecialchars($settings['about_chefs_count'] ?? '30'); ?></h1>
                                    <div class="ps-4">
                                        <p class="mb-0">Professional</p>
                                        <h6 class="text-uppercase mb-0">Chefs</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center border-start border-5 border-primary px-3">
                                    <h1 class="flex-shrink-0 display-5 text-primary mb-0" data-toggle="counter-up"><?php echo htmlspecialchars($settings['about_menu_items'] ?? '20'); ?></h1>
                                    <div class="ps-4">
                                        <p class="mb-0">Menu</p>
                                        <h6 class="text-uppercase mb-0">Items</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="btn btn-primary py-3 px-5 mt-2" href="menu.php">View Menu</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->
        
        <!-- Mission & History Start -->
        <?php if (!empty($settings['about_mission']) || !empty($settings['about_history'])): ?>
        <div class="container-xxl py-5">
            <div class="container">
                <div class="row g-5">
                    <?php if (!empty($settings['about_mission'])): ?>
                    <div class="col-lg-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-5">
                                <h5 class="section-title ff-secondary text-start text-primary fw-normal mb-3">Our Mission</h5>
                                <div class="about-text">
                                    <?php echo $settings['about_mission']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['about_history'])): ?>
                    <div class="col-lg-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-5">
                                <h5 class="section-title ff-secondary text-start text-primary fw-normal mb-3">Our History</h5>
                                <div class="about-text">
                                    <?php echo $settings['about_history']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Mission & History End -->
        <?php endif; ?>    

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-4 col-md-6">
                        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4"><?php echo htmlspecialchars($settings['site_name'] ?? 'Noob Restaurant'); ?></h4>
                        <a class="btn btn-link" href="about.php">About Us</a>
                        <a class="btn btn-link" href="contact.php">Contact Us</a>
                        <a class="btn btn-link" href="menu.php">Order Food</a>
                        <a class="btn btn-link" href="contact.php">Address</a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Contact</h4>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i><?php echo htmlspecialchars($settings['site_address'] ?? 'Ethiopia, Addis Ababa, Jemo'); ?></p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i><?php echo htmlspecialchars($settings['site_phone'] ?? '+251 911******'); ?></p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i><?php echo htmlspecialchars($settings['site_email'] ?? 'Nobrestaurant@gmail.com'); ?></p>
                        <div class="d-flex pt-2">
                            <?php if (!empty($settings['twitter_url'])): ?>
                            <a class="btn btn-outline-light btn-social" href="<?php echo htmlspecialchars($settings['twitter_url']); ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                            <?php endif; ?>
                            <?php if (!empty($settings['facebook_url'])): ?>
                            <a class="btn btn-outline-light btn-social" href="<?php echo htmlspecialchars($settings['facebook_url']); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <?php endif; ?>
                            <?php if (!empty($settings['youtube_url'])): ?>
                            <a class="btn btn-outline-light btn-social" href="<?php echo htmlspecialchars($settings['youtube_url']); ?>" target="_blank"><i class="fab fa-youtube"></i></a>
                            <?php endif; ?>
                            <?php if (!empty($settings['instagram_url'])): ?>
                            <a class="btn btn-outline-light btn-social" href="<?php echo htmlspecialchars($settings['instagram_url']); ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Opening</h4>
                        <h5 class="text-light fw-normal"><?php echo htmlspecialchars($settings['opening_hours'] ?? 'Monday - Sunday'); ?></h5>
                        <p>01AM - 04PM</p>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#"><?php echo htmlspecialchars($settings['site_name'] ?? 'Noob Restaurant'); ?></a>, All Right Reserved. 
                            Designed By <a class="border-bottom" href="#">MAME</a>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <div class="footer-menu">
                                <a href="index.php">Home</a>
                                <a href="menu.php">Menu</a>
                                <a href="about.php">About</a>
                                <a href="contact.php">Contact</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
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
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html> 