<?php
// admin/dashboard.php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/database.php';
$database = new Database();
$db = $database->getConnection();

// Get counts
$products_count = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
$reviews_count = $db->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Maison</title>
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
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #1a1a1a;
            color: white;
            padding: 2rem 0;
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
        
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem;
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
        
        .logout-btn {
            padding: 0.5rem 1rem;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 300;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .section-header h2 {
            font-weight: 400;
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
        
        .btn-sm {
            padding: 0.3rem 0.8rem;
            font-size: 0.8rem;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            text-align: left;
            padding: 1rem 0.5rem;
            border-bottom: 2px solid #eee;
            color: #555;
            font-weight: 500;
        }
        
        td {
            padding: 1rem 0.5rem;
            border-bottom: 1px solid #eee;
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
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-logo">MAISON</div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="products.php">Products</a></li>
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
                <h1>Dashboard</h1>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $products_count; ?></div>
                    <div class="stat-label">Total Products</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $reviews_count; ?></div>
                    <div class="stat-label">Total Reviews</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">6</div>
                    <div class="stat-label">Categories</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">3</div>
                    <div class="stat-label">Gallery Images</div>
                </div>
            </div>
            
            <!-- Recent Products -->
            <div class="section">
                <div class="section-header">
                    <h2>Recent Products</h2>
                    <a href="products.php?action=add" class="btn">Add New Product</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Rating</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recent_query = "SELECT * FROM products ORDER BY id DESC LIMIT 5";
                        $recent_stmt = $db->prepare($recent_query);
                        $recent_stmt->execute();
                        $recent_products = $recent_stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach($recent_products as $product):
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo $product['category']; ?></td>
                            <td>$<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo $product['rating']; ?></td>
                            <td class="action-links">
                                <a href="products.php?action=edit&id=<?php echo $product['id']; ?>">Edit</a>
                                <a href="products.php?action=delete&id=<?php echo $product['id']; ?>" class="delete" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Recent Reviews -->
            <div class="section">
                <div class="section-header">
                    <h2>Recent Reviews</h2>
                    <a href="reviews.php?action=add" class="btn">Add New Review</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recent_reviews = $db->query("SELECT * FROM reviews ORDER BY id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach($recent_reviews as $review):
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($review['customer_name']); ?></td>
                            <td><?php echo $review['rating']; ?> ★</td>
                            <td><?php echo substr(htmlspecialchars($review['comment']), 0, 50); ?>...</td>
                            <td class="action-links">
                                <a href="reviews.php?action=edit&id=<?php echo $review['id']; ?>">Edit</a>
                                <a href="reviews.php?action=delete&id=<?php echo $review['id']; ?>" class="delete" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>