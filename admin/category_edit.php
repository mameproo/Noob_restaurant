<?php
require_once '../config.php';

// Check if user is logged in and is admin
if (!is_logged_in() || !is_admin()) {
    redirect_with_message('login.php', 'Please login to access the admin panel.', 'warning');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate required fields
    $required_fields = ['name', 'name_am', 'icon'];
    $missing_fields = [];
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }
    
    if (!empty($missing_fields)) {
        setFlashMessage('error', 'Please fill in all required fields: ' . implode(', ', $missing_fields));
        redirect('categories.php');
    }
    
    // Sanitize input
    $name = sanitize_input($_POST['name']);
    $name_am = sanitize_input($_POST['name_am']);
    $description = sanitize_input($_POST['description']);
    $icon = sanitize_input($_POST['icon']);
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    try {
        if ($id > 0) {
            // Update existing category
            $sql = "UPDATE menu_categories SET name = ?, name_am = ?, description = ?, icon = ? WHERE id = ?";
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssssi", $name, $name_am, $description, $icon, $id);
                if (mysqli_stmt_execute($stmt)) {
                    setFlashMessage('success', 'Category updated successfully.');
                } else {
                    throw new Exception('Error updating category.');
                }
                mysqli_stmt_close($stmt);
            }
        } else {
            // Insert new category
            $sql = "INSERT INTO menu_categories (name, name_am, description, icon) VALUES (?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssss", $name, $name_am, $description, $icon);
                if (mysqli_stmt_execute($stmt)) {
                    setFlashMessage('success', 'Category added successfully.');
                } else {
                    throw new Exception('Error adding category.');
                }
                mysqli_stmt_close($stmt);
            }
        }
    } catch (Exception $e) {
        setFlashMessage('error', $e->getMessage());
    }
}

redirect('categories.php');
?> 