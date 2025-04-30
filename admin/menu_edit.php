<?php
require_once '../config.php';

// Check if user is logged in and is admin
if (!is_logged_in() || !is_admin()) {
    redirect_with_message('login.php', 'Please login to access the admin panel.', 'warning');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$item = [
    'id' => 0,
    'category_id' => '',
    'name' => '',
    'name_am' => '',
    'description' => '',
    'description_am' => '',
    'price' => '',
    'image' => '',
    'is_available' => 1
];

// Get categories for dropdown
$categories = mysqli_query($conn, "SELECT id, name FROM menu_categories ORDER BY name");

// If editing existing item
if ($id > 0) {
    $sql = "SELECT * FROM menu_items WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $item = $row;
            }
        }
        mysqli_stmt_close($stmt);
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item['name'] = sanitize_input($_POST['name']);
    $item['name_am'] = sanitize_input($_POST['name_am']);
    $item['description'] = sanitize_input($_POST['description']);
    $item['description_am'] = sanitize_input($_POST['description_am']);
    $item['price'] = (float)$_POST['price'];
    $item['category_id'] = (int)$_POST['category_id'];
    $item['is_available'] = isset($_POST['is_available']) ? 1 : 0;
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = uniqid() . '.' . $ext;
            $upload_path = '../img/menu/' . $new_filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $item['image'] = $new_filename;
            } else {
                setFlashMessage('error', 'Error uploading image.');
                redirect_with_message('menu_edit.php?id=' . $id, 'Error uploading image.', 'error');
            }
        } else {
            setFlashMessage('error', 'Invalid image format. Allowed formats: ' . implode(', ', $allowed));
            redirect_with_message('menu_edit.php?id=' . $id, 'Invalid image format.', 'error');
        }
    }
    
    if ($id > 0) {
        // Update existing item
        $sql = "UPDATE menu_items SET 
                name = ?, name_am = ?, description = ?, description_am = ?,
                price = ?, category_id = ?, is_available = ?";
        $params = [$item['name'], $item['name_am'], $item['description'], 
                  $item['description_am'], $item['price'], $item['category_id'], 
                  $item['is_available']];
        $types = "ssssdii";
        
        if (isset($item['image'])) {
            $sql .= ", image = ?";
            $params[] = $item['image'];
            $types .= "s";
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        $types .= "i";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            if (mysqli_stmt_execute($stmt)) {
                setFlashMessage('success', 'Menu item updated successfully.');
                redirect_with_message('menu.php', 'Menu item updated successfully.', 'success');
            } else {
                setFlashMessage('error', 'Error updating menu item.');
                redirect_with_message('menu_edit.php?id=' . $id, 'Error updating menu item.', 'error');
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        // Insert new item
        $sql = "INSERT INTO menu_items (name, name_am, description, description_am, 
                price, category_id, is_available, image) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssdiss", 
                $item['name'], $item['name_am'], $item['description'], 
                $item['description_am'], $item['price'], $item['category_id'], 
                $item['is_available'], $item['image']);
            if (mysqli_stmt_execute($stmt)) {
                setFlashMessage('success', 'Menu item added successfully.');
                redirect_with_message('menu.php', 'Menu item added successfully.', 'success');
            } else {
                setFlashMessage('error', 'Error adding menu item.');
                redirect_with_message('menu_edit.php', 'Error adding menu item.', 'error');
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Edit' : 'Add'; ?> Menu Item - <?php echo SITE_NAME; ?></title>
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
                    <h1 class="h2"><?php echo $id ? 'Edit' : 'Add'; ?> Menu Item</h1>
                </div>

                <?php
                $flash = getFlashMessage();
                if ($flash): ?>
                    <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show" role="alert">
                        <?php echo $flash['message']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select name="category_id" id="category_id" class="form-select" required>
                                        <option value="">Select Category</option>
                                        <?php while ($category = mysqli_fetch_assoc($categories)): ?>
                                            <option value="<?php echo $category['id']; ?>" 
                                                    <?php echo $item['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Price (ETB)</label>
                                    <input type="number" name="price" id="price" class="form-control" 
                                           value="<?php echo $item['price']; ?>" step="0.01" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Name (English)</label>
                                    <input type="text" name="name" id="name" class="form-control" 
                                           value="<?php echo htmlspecialchars($item['name']); ?>" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="name_am" class="form-label">Name (Amharic)</label>
                                    <input type="text" name="name_am" id="name_am" class="form-control" 
                                           value="<?php echo htmlspecialchars($item['name_am']); ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="description" class="form-label">Description (English)</label>
                                    <textarea name="description" id="description" class="form-control" rows="3"><?php echo htmlspecialchars($item['description']); ?></textarea>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="description_am" class="form-label">Description (Amharic)</label>
                                    <textarea name="description_am" id="description_am" class="form-control" rows="3"><?php echo htmlspecialchars($item['description_am']); ?></textarea>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <?php if ($item['image']): ?>
                                    <div class="mb-2">
                                        <img src="../img/menu/<?php echo htmlspecialchars($item['image']); ?>" 
                                             alt="Current image" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="is_available" id="is_available" class="form-check-input" 
                                           <?php echo $item['is_available'] ? 'checked' : ''; ?>>
                                    <label for="is_available" class="form-check-label">Available</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    <?php echo $id ? 'Update' : 'Add'; ?> Menu Item
                                </button>
                                <a href="menu.php" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Cancel
                                </a>
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