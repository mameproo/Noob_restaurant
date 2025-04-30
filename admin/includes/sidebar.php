<?php
// Get unread feedback count
$unread_feedback = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM feedback WHERE is_read = 0"))['count'];
?>
<li class="nav-item">
    <a class="nav-link <?php echo is_current_page('menu.php') ? 'active' : ''; ?>" href="menu.php">
        <i class="fas fa-utensils me-2"></i>
        Menu Management
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?php echo is_current_page('about_settings.php') ? 'active' : ''; ?>" href="about_settings.php">
        <i class="fas fa-info-circle me-2"></i>
        About Page Settings
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?php echo is_current_page('feedback.php') ? 'active' : ''; ?>" href="feedback.php">
        <i class="fas fa-envelope me-2"></i>
        Feedback
        <?php if ($unread_feedback > 0): ?>
            <span class="badge bg-danger"><?php echo $unread_feedback; ?></span>
        <?php endif; ?>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?php echo is_current_page('testimonials.php') ? 'active' : ''; ?>" href="testimonials.php">
        <i class="fas fa-comments me-2"></i>
        Testimonials
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?php echo is_current_page('settings.php') ? 'active' : ''; ?>" href="settings.php">
        <i class="fas fa-cog me-2"></i>
        Settings
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?php echo is_current_page('logout.php') ? 'active' : ''; ?>" href="logout.php">
        <i class="fas fa-sign-out-alt me-2"></i>
        Logout
    </a>
</li> 