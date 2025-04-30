<?php
require_once '../config.php';

// Check if user is logged in and is admin
if (!is_logged_in() || !is_admin()) {
    redirect_with_message('login.php', 'Please login to access the admin panel.', 'warning');
}

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $sql = "DELETE FROM feedback WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            setFlashMessage('success', 'Feedback deleted successfully.');
        } else {
            setFlashMessage('error', 'Error deleting feedback.');
        }
        mysqli_stmt_close($stmt);
    }
    redirect_with_message('feedback.php', 'Feedback deleted successfully.', 'success');
}

// Handle mark as read action
if (isset($_GET['read']) && is_numeric($_GET['read'])) {
    $id = (int)$_GET['read'];
    $sql = "UPDATE feedback SET is_read = 1 WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            setFlashMessage('success', 'Feedback marked as read.');
        } else {
            setFlashMessage('error', 'Error updating feedback.');
        }
        mysqli_stmt_close($stmt);
    }
    redirect_with_message('feedback.php', 'Feedback marked as read.', 'success');
}

// Get filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Build query based on filter
$sql = "SELECT * FROM feedback";
if ($filter == 'unread') {
    $sql .= " WHERE is_read = 0";
} elseif ($filter == 'read') {
    $sql .= " WHERE is_read = 1";
}
$sql .= " ORDER BY created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedback - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                            <a class="nav-link text-white" href="index.php">
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
                        </li>
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
                    <h1 class="h2">Manage Feedback</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a href="feedback.php" class="btn btn-sm btn-outline-secondary <?php echo $filter == 'all' ? 'active' : ''; ?>">
                                All
                            </a>
                            <a href="feedback.php?filter=unread" class="btn btn-sm btn-outline-secondary <?php echo $filter == 'unread' ? 'active' : ''; ?>">
                                Unread
                            </a>
                            <a href="feedback.php?filter=read" class="btn btn-sm btn-outline-secondary <?php echo $filter == 'read' ? 'active' : ''; ?>">
                                Read
                            </a>
                        </div>
                    </div>
                </div>

                <?php
                $flash = getFlashMessage();
                if ($flash): ?>
                    <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show" role="alert">
                        <?php echo $flash['message']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                                    <td>
                                        <?php if ($row['is_read']): ?>
                                            <span class="badge bg-success">Read</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Unread</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!$row['is_read']): ?>
                                            <a href="feedback.php?read=<?php echo $row['id']; ?>" 
                                               class="btn btn-sm btn-success"
                                               onclick="return confirm('Are you sure you want to mark this as read?')">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        <?php endif; ?>
                                        <button type="button" 
                                                class="btn btn-sm btn-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#replyModal<?php echo $row['id']; ?>">
                                            <i class="fas fa-reply"></i>
                                        </button>
                                        <a href="feedback.php?delete=<?php echo $row['id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this feedback?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- Reply Modal -->
                                <div class="modal fade" id="replyModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="replyModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="replyModalLabel<?php echo $row['id']; ?>">Reply to <?php echo htmlspecialchars($row['name']); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="send_reply.php" method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="feedback_id" value="<?php echo $row['id']; ?>">
                                                    <input type="hidden" name="customer_email" value="<?php echo htmlspecialchars($row['email']); ?>">
                                                    <input type="hidden" name="customer_name" value="<?php echo htmlspecialchars($row['name']); ?>">
                                                    
                                                    <div class="mb-3">
                                                        <label for="subject<?php echo $row['id']; ?>" class="form-label">Subject</label>
                                                        <input type="text" class="form-control" id="subject<?php echo $row['id']; ?>" name="subject" value="Re: <?php echo htmlspecialchars($row['subject']); ?>" required>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="message<?php echo $row['id']; ?>" class="form-label">Message</label>
                                                        <textarea class="form-control" id="message<?php echo $row['id']; ?>" name="message" rows="5" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Send Reply</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

<!-- Bootstrap Bundle includes Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 