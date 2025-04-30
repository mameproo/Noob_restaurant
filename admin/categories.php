<?php
require_once '../config.php';

// Check if user is logged in and is admin
if (!is_logged_in() || !is_admin()) {
    redirect_with_message('login.php', 'Please login to access the admin panel.', 'warning');
}

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $sql = "DELETE FROM menu_categories WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            setFlashMessage('success', 'Category and associated menu items deleted successfully.');
        } else {
            setFlashMessage('error', 'Error deleting category.');
        }
        mysqli_stmt_close($stmt);
    }
    redirect('categories.php');
}

// Get all categories
$sql = "SELECT * FROM menu_categories ORDER BY name";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - <?php echo SITE_NAME; ?></title>
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
                    <h1 class="h2">Manage Categories</h1>
                    <a href="#" id="openIconSelector" style="font-weight: bold; font-size: 26px; color: #4CAF50; border: 1px dotted black; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
  <i class="fa-solid fa-exclamation"></i> Icons
</a>

                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-primary" id="addCategoryBtn">
                            <i class="fas fa-plus me-1"></i>
                            Add New Category
                        </button>
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
                                <th>Icon</th>
                                <th>Name</th>
                                <th>Name (Amharic)</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td>
                                        <i class="fas <?php echo htmlspecialchars($row['icon']); ?> fa-2x"></i>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name_am']); ?></td>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary edit-category" 
                                                data-id="<?php echo $row['id']; ?>"
                                                data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                                data-name-am="<?php echo htmlspecialchars($row['name_am']); ?>"
                                                data-description="<?php echo htmlspecialchars($row['description']); ?>"
                                                data-icon="<?php echo htmlspecialchars($row['icon']); ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="categories.php?delete=<?php echo $row['id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this category? This will also delete all menu items in this category.')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="category_add.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name (English)</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="name_am" class="form-label">Name (Amharic)</label>
                            <input type="text" name="name_am" id="name_am" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="icon" class="form-label">Icon Class</label>
                            <input type="text" name="icon" id="icon" class="form-control" 
                                   placeholder="e.g., fa-coffee" required>
                            <small class="text-muted">Choose an icon from <a href="#" id="openIconSelector">Food & Drink Icons</a></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="category_edit.php" method="post">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Name (English)</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_name_am" class="form-label">Name (Amharic)</label>
                            <input type="text" name="name_am" id="edit_name_am" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_icon" class="form-label">Icon Class</label>
                            <input type="text" name="icon" id="edit_icon" class="form-control" required>
                            <small class="text-muted">Choose an icon from <a href="#" id="openIconSelector">Food & Drink Icons</a></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Icon Selector Modal -->
    <div id="iconModal" class="modal fade" tabindex="-1" aria-labelledby="iconModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="iconModalLabel">Food & Drink Icons</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="icon-list">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="fas fa-coffee me-2 fs-4"></i>
                                    <span class="me-auto">fa-coffee</span>
                                    <button class="btn btn-sm btn-primary copy-icon" data-icon="fa-coffee">Copy</button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="fas fa-apple-alt me-2 fs-4"></i>
                                    <span class="me-auto">fa-apple-alt</span>
                                    <button class="btn btn-sm btn-primary copy-icon" data-icon="fa-apple-alt">Copy</button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="fas fa-beer me-2 fs-4"></i>
                                    <span class="me-auto">fa-beer</span>
                                    <button class="btn btn-sm btn-primary copy-icon" data-icon="fa-beer">Copy</button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="fas fa-pizza-slice me-2 fs-4"></i>
                                    <span class="me-auto">fa-pizza-slice</span>
                                    <button class="btn btn-sm btn-primary copy-icon" data-icon="fa-pizza-slice">Copy</button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="fas fa-ice-cream me-2 fs-4"></i>
                                    <span class="me-auto">fa-ice-cream</span>
                                    <button class="btn btn-sm btn-primary copy-icon" data-icon="fa-ice-cream">Copy</button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="fas fa-hamburger me-2 fs-4"></i>
                                    <span class="me-auto">fa-hamburger</span>
                                    <button class="btn btn-sm btn-primary copy-icon" data-icon="fa-hamburger">Copy</button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="fas fa-hotdog me-2 fs-4"></i>
                                    <span class="me-auto">fa-hotdog</span>
                                    <button class="btn btn-sm btn-primary copy-icon" data-icon="fa-hotdog">Copy</button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="fas fa-glass-martini me-2 fs-4"></i>
                                    <span class="me-auto">fa-glass-martini</span>
                                    <button class="btn btn-sm btn-primary copy-icon" data-icon="fa-glass-martini">Copy</button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="fas fa-wine-glass me-2 fs-4"></i>
                                    <span class="me-auto">fa-wine-glass</span>
                                    <button class="btn btn-sm btn-primary copy-icon" data-icon="fa-wine-glass">Copy</button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="fas fa-bacon me-2 fs-4"></i>
                                    <span class="me-auto">fa-bacon</span>
                                    <button class="btn btn-sm btn-primary copy-icon" data-icon="fa-bacon">Copy</button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="fas fa-carrot me-2 fs-4"></i>
                                    <span class="me-auto">fa-carrot</span>
                                    <button class="btn btn-sm btn-primary copy-icon" data-icon="fa-carrot">Copy</button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="fas fa-cake me-2 fs-4"></i>
                                    <span class="me-auto">fa-cake</span>
                                    <button class="btn btn-sm btn-primary copy-icon" data-icon="fa-cake">Copy</button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="fas fa-fish me-2 fs-4"></i>
                                    <span class="me-auto">fa-fish</span>
                                    <button class="btn btn-sm btn-primary copy-icon" data-icon="fa-fish">Copy</button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="fas fa-bread-slice me-2 fs-4"></i>
                                    <span class="me-auto">fa-bread-slice</span>
                                    <button class="btn btn-sm btn-primary copy-icon" data-icon="fa-bread-slice">Copy</button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="fas fa-pepper-hot me-2 fs-4"></i>
                                    <span class="me-auto">fa-pepper-hot</span>
                                    <button class="btn btn-sm btn-primary copy-icon" data-icon="fa-pepper-hot">Copy</button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                    <i class="fas fa-mug-hot me-2 fs-4"></i>
                                    <span class="me-auto">fa-mug-hot</span>
                                    <button class="btn btn-sm btn-primary copy-icon" data-icon="fa-mug-hot">Copy</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<!-- Bootstrap Bundle includes Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Wait for the DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Get modal elements
            const addModal = document.getElementById('addCategoryModal');
            const editModal = document.getElementById('editCategoryModal');
            
            // Initialize Bootstrap modals
            const addModalInstance = new bootstrap.Modal(addModal);
            const editModalInstance = new bootstrap.Modal(editModal);
            
            // Add Category button click handler
            document.getElementById('addCategoryBtn').addEventListener('click', function() {
                // Reset the form
                document.querySelector('#addCategoryModal form').reset();
                // Show the modal
                addModalInstance.show();
            });
            
            // Edit Category button click handlers
            document.querySelectorAll('.edit-category').forEach(button => {
                button.addEventListener('click', function(e) {
                    // Prevent default action
                    e.preventDefault();
                    
                    // Get data attributes
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const nameAm = this.getAttribute('data-name-am');
                    const description = this.getAttribute('data-description');
                    const icon = this.getAttribute('data-icon');
                    
                    // Set form values
                    document.getElementById('edit_id').value = id;
                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_name_am').value = nameAm;
                    document.getElementById('edit_description').value = description;
                    document.getElementById('edit_icon').value = icon;
                    
                    // Show the modal
                    editModalInstance.show();
                });
            });
            
            // Form submission handlers
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Disable submit button to prevent double submission
                    const submitButton = this.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                    }
                });
            });
            
            // Reset forms when modals are closed
            addModal.addEventListener('hidden.bs.modal', function() {
                document.querySelector('#addCategoryModal form').reset();
            });
            
            editModal.addEventListener('hidden.bs.modal', function() {
                document.querySelector('#editCategoryModal form').reset();
            });
            
            // Debug information
            console.log('Categories page initialized');
            console.log('Add modal:', addModal);
            console.log('Edit modal:', editModal);

            // Open icon selector modal
            document.getElementById('openIconSelector').addEventListener('click', function(e) {
                e.preventDefault();
                var iconModal = new bootstrap.Modal(document.getElementById('iconModal'));
                iconModal.show();
            });
            
            // Copy icon class to input field
            document.querySelectorAll('.copy-icon').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    var iconClass = this.getAttribute('data-icon');
                    var activeInput = document.activeElement;
                    
                    // If no input is focused, use the edit_icon input
                    if (!activeInput || !activeInput.classList.contains('form-control')) {
                        activeInput = document.getElementById('edit_icon');
                    }
                    
                    if (activeInput) {
                        activeInput.value = iconClass;
                        activeInput.focus();
                        
                        // Show feedback
                        var originalText = this.textContent;
                        this.textContent = 'Copied!';
                        this.disabled = true;
                        
                        setTimeout(function() {
                            button.textContent = originalText;
                            button.disabled = false;
                        }, 1500);
                    }
                });
            });
        });
    </script>
</body>
</html> 