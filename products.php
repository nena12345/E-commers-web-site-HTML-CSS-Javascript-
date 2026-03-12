<?php
// admin/products.php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/database.php';
$database = new Database();
$db = $database->getConnection();

// Handle Add/Edit/Delete
if(isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if($action == 'delete' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $delete_query = "DELETE FROM products WHERE id = :id";
        $delete_stmt = $db->prepare($delete_query);
        $delete_stmt->bindParam(':id', $id);
        $delete_stmt->execute();
        header("Location: products.php?msg=deleted");
        exit();
    }
    
    if($action == 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $rating = $_POST['rating'];
        $featured = isset($_POST['featured']) ? 1 : 0;
        
        $insert_query = "INSERT INTO products (name, price, category, description, rating, featured) 
                         VALUES (:name, :price, :category, :description, :rating, :featured)";
        $insert_stmt = $db->prepare($insert_query);
        $insert_stmt->bindParam(':name', $name);
        $insert_stmt->bindParam(':price', $price);
        $insert_stmt->bindParam(':category', $category);
        $insert_stmt->bindParam(':description', $description);
        $insert_stmt->bindParam(':rating', $rating);
        $insert_stmt->bindParam(':featured', $featured);
        
        if($insert_stmt->execute()) {
            header("Location: products.php?msg=added");
            exit();
        }
    }
    
    if($action == 'edit' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $rating = $_POST['rating'];
        $featured = isset($_POST['featured']) ? 1 : 0;
        
        $update_query = "UPDATE products SET name=:name, price=:price, category=:category, 
                        description=:description, rating=:rating, featured=:featured WHERE id=:id";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(':id', $id);
        $update_stmt->bindParam(':name', $name);
        $update_stmt->bindParam(':price', $price);
        $update_stmt->bindParam(':category', $category);
        $update_stmt->bindParam(':description', $description);
        $update_stmt->bindParam(':rating', $rating);
        $update_stmt->bindParam(':featured', $featured);
        
        if($update_stmt->execute()) {
            header("Location: products.php?msg=updated");
            exit();
        }
    }
}

// Get all products
$products_query = "SELECT * FROM products ORDER BY id DESC";
$products_stmt = $db->prepare($products_query);
$products_stmt->execute();
$products = $products_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get product for editing if needed
$edit_product = null;
if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $edit_id = $_GET['id'];
    $edit_query = "SELECT * FROM products WHERE id = :id";
    $edit_stmt = $db->prepare($edit_query);
    $edit_stmt->bindParam(':id', $edit_id);
    $edit_stmt->execute();
    $edit_product = $edit_stmt->fetch(PDO::FETCH_ASSOC);
}

$categories = ['HOME', 'CLOTHING', 'ELECTRONICS', 'ACCESSORIES', 'BEAUTY', 'BOOKS'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Maison Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        body {
            background: #f5f5f5;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background: #1a1a1a;
            color: white;
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
        }
        
        .sidebar-logo {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 300;
            letter-spacing: 3px;
            padding: 1rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid #333;
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 0.8rem 1.5rem;
            color: #999;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #333;
            color: white;
        }
        
        .main-content {
            flex: 1;
            padding: 2rem;
            margin-left: 250px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #ddd;
        }
        
        .header h1 {
            font-weight: 300;
            color: #333;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
        }
        
        .btn-success {
            background: #28a745;
        }
        
        .message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        
        .product-form {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-size: 0.9rem;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .checkbox-group input {
            width: auto;
        }
        
        table {
            width: 100%;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        th {
            background: #f8f9fa;
            padding: 1rem;
            text-align: left;
            font-weight: 500;
            color: #555;
        }
        
        td {
            padding: 1rem;
            border-top: 1px solid #eee;
        }
        
        .action-links a {
            color: #007bff;
            text-decoration: none;
            margin-right: 0.5rem;
            font-size: 0.9rem;
        }
        
        .action-links a.delete {
            color: #dc3545;
        }
        
        .featured-badge {
            background: #28a745;
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-logo">MAISON</div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="products.php" class="active">Products</a></li>
                <li><a href="reviews.php">Reviews</a></li>
                <li><a href="gallery.php">Gallery</a></li>
                <li><a href="contact.php">Contact Info</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Manage Products</h1>
                <a href="?action=add" class="btn">Add New Product</a>
            </div>
            
            <?php if(isset($_GET['msg'])): ?>
                <div class="message">
                    <?php 
                        if($_GET['msg'] == 'added') echo "Product added successfully!";
                        if($_GET['msg'] == 'updated') echo "Product updated successfully!";
                        if($_GET['msg'] == 'deleted') echo "Product deleted successfully!";
                    ?>
                </div>
            <?php endif; ?>
            
            <!-- Add/Edit Form -->
            <?php if(isset($_GET['action']) && ($_GET['action'] == 'add' || $_GET['action'] == 'edit')): ?>
                <div class="product-form">
                    <h2 style="margin-bottom: 1.5rem; font-weight: 400;">
                        <?php echo $_GET['action'] == 'add' ? 'Add New Product' : 'Edit Product'; ?>
                    </h2>
                    
                    <form method="POST" action="?action=<?php echo $_GET['action']; ?><?php echo isset($_GET['id']) ? '&id='.$_GET['id'] : ''; ?>">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="name" required value="<?php echo $edit_product ? htmlspecialchars($edit_product['name']) : ''; ?>">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Price ($)</label>
                                <input type="number" step="0.01" name="price" required value="<?php echo $edit_product ? $edit_product['price'] : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Category</label>
                                <select name="category" required>
                                    <option value="">Select Category</option>
                                    <?php foreach($categories as $cat): ?>
                                        <option value="<?php echo $cat; ?>" <?php echo ($edit_product && $edit_product['category'] == $cat) ? 'selected' : ''; ?>>
                                            <?php echo $cat; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Rating (0-5)</label>
                                <input type="number" step="0.1" min="0" max="5" name="rating" value="<?php echo $edit_product ? $edit_product['rating'] : '4.5'; ?>">
                            </div>
                            
                            <div class="form-group checkbox-group">
                                <label>
                                    <input type="checkbox" name="featured" <?php echo ($edit_product && $edit_product['featured']) ? 'checked' : ''; ?>>
                                    Featured Product
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="4"><?php echo $edit_product ? htmlspecialchars($edit_product['description']) : ''; ?></textarea>
                        </div>
                        
                        <div style="display: flex; gap: 1rem;">
                            <button type="submit" class="btn btn-success">Save Product</button>
                            <a href="products.php" class="btn" style="background: #6c757d;">Cancel</a>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
            
            <!-- Products Table -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Rating</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($products as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo $product['category']; ?></td>
                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo $product['rating']; ?> ★</td>
                        <td>
                            <?php if($product['featured']): ?>
                                <span class="featured-badge">Featured</span>
                            <?php endif; ?>
                        </td>
                        <td class="action-links">
                            <a href="?action=edit&id=<?php echo $product['id']; ?>">Edit</a>
                            <a href="?action=delete&id=<?php echo $product['id']; ?>" class="delete" onclick="return confirm('Delete this product?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>