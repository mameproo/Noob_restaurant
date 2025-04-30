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

// Get approved testimonials
$testimonials = mysqli_query($conn, "SELECT * FROM testimonials WHERE is_approved = 1 ORDER BY created_at DESC LIMIT 6");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($settings['site_name']); ?></title>
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

        <!-- Navbar & Hero Start -->
        <div class="container-xxl position-relative p-0">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 px-lg-5 py-3 py-lg-0">
                <a href="" class="navbar-brand p-0">
                    <h1 class="text-primary m-0"><i class="fa fa-utensils me-3"></i><?php echo htmlspecialchars($settings['site_name']); ?></h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0 pe-4">
                        <a href="index.php" class="nav-item nav-link active">Home</a>
                        <a href="menu.php" class="nav-item nav-link">Menu</a>
                        <a href="about.php" class="nav-item nav-link">About</a>
                        <a href="service.php" class="nav-item nav-link">Service</a>
                        <a href="contact.php" class="nav-item nav-link">Contact</a>
                    </div>
                </div>
            </nav>

            <div class="container-xxl py-5 bg-dark hero-header mb-5">
                <div class="container my-5 py-5">
                    <div class="row align-items-center g-5">
                        <div class="col-lg-6 text-center text-lg-start">
                            <h1 class="display-3 text-white animated slideInLeft">Enjoy With<br>Shiro</h1>
                            <p id="amhtxt" class="text-white animated slideInLeft mb-4 pb-2"> ኖብ ሬስቶራንት የተለያዩ የፆምና የፍስክ ምግቦችን በጣፋጭ አዘገጋጀትና ከጥሩ መስተንግዶ ጋር ያገኛሉ!</p>
                            <a href="menu.php" class="btn btn-primary py-sm-3 px-sm-5 me-3 animated slideInLeft">Food Menu</a>
                        </div>
                        <div class="col-lg-6 text-center text-lg-end overflow-hidden">
                            <img class="img-fluid" src="img/hero.png" alt="">
                        </div>
                    </div>
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

        <!-- Service Start -->
        <div class="container-xxl py-5 bg-dark">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="service-item rounded pt-3">
                            <div class="p-4">
                                <i class="fa fa-3x fa-user-tie text-primary mb-4"></i>
                                <h5>Warmth</h5>
                                <p>Warm welcome with exceptional service for a great dining experience.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="service-item rounded pt-3">
                            <div class="p-4">
                                <i class="fa fa-3x fa-utensils text-primary mb-4"></i>
                                <h5>Quality Food</h5>
                                <p>Fresh, high-quality meals served with excellence.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.6s">
                        <div class="service-item rounded pt-3">
                            <div class="p-4">
                                <i class="fa fa-3x fa-cart-plus text-primary mb-4"></i>
                                <h5>Order Delivery</h5>
                                <p>Order delivery for nearby locations</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="service-item rounded pt-3">
                            <div class="p-4">
                                <i class="fa fa-3x fa-headset text-primary mb-4"></i>
                                <h5>Customer Service</h5>
                                <p>We can communicate and receive comments from our customers.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Service End -->

        <!-- Menu Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h5 class="section-title ff-secondary text-center text-primary fw-normal">Food Menu</h5>
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

        <!-- Testimonial Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h5 class="section-title ff-secondary text-center text-primary fw-normal">Testimonials</h5>
                    <h1 class="mb-5">Our Customers Say!!!</h1>
                </div>
                <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
                    <?php while ($testimonial = mysqli_fetch_assoc($testimonials)): ?>
                        <div class="testimonial-item bg-transparent border rounded p-4">
                            <div class="d-flex align-items-center">
                                <div class="ps-3">
                                    <h5 class="mb-1"><?php echo htmlspecialchars($testimonial['name']); ?></h5>
                                    <span>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fa fa-star <?php echo $i <= $testimonial['rating'] ? 'text-primary' : ''; ?>"></i>
                                        <?php endfor; ?>
                                    </span>
                                </div>
                            </div>
                            <p class="mt-4"><?php echo htmlspecialchars($testimonial['comment']); ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <!-- Testimonial End -->

        <!-- Testimonial Form Start -->
        <div class="container-xxl py-5" id="testimonial-form">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h5 class="section-title ff-secondary text-center text-primary fw-normal">Share Your Experience</h5>
                    <h1 class="mb-5">Write a Review</h1>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <?php if (isset($_SESSION['flash_message'])): ?>
                            <div class="alert alert-<?php echo $_SESSION['flash_type']; ?> alert-dismissible fade show" role="alert">
                                <?php echo $_SESSION['flash_message']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
                        <?php endif; ?>
                        
                        <form action="submit_testimonial.php" method="POST" class="wow fadeInUp" data-wow-delay="0.1s">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required>
                                        <label for="name">Your Name</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="comment" name="comment" placeholder="Your Comment" style="height: 150px" required></textarea>
                                        <label for="comment">Your Comment</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <select class="form-select" id="rating" name="rating" required>
                                            <option value="">Select Rating</option>
                                            <option value="5">5 Stars - Excellent</option>
                                            <option value="4">4 Stars - Very Good</option>
                                            <option value="3">3 Stars - Good</option>
                                            <option value="2">2 Stars - Fair</option>
                                            <option value="1">1 Star - Poor</option>
                                        </select>
                                        <label for="rating">Your Rating</label>
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <button class="btn btn-primary w-100 py-3" type="submit">Submit Review</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Testimonial Form End -->

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