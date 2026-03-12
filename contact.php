<?php
// admin/contact.php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/database.php';
$database = new Database();
$db = $database->getConnection();

// Get current contact info
$contact_query = "SELECT * FROM contact_info LIMIT 1";
$contact_stmt = $db->prepare($contact_query);
$contact_stmt->execute();
$contact = $contact_stmt->fetch(PDO::FETCH_ASSOC);

// Handle update
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $whatsapp = $_POST['whatsapp'];
    
    $update_query = "UPDATE contact_info SET phone=:phone, email=:email, address=:address, whatsapp=:whatsapp WHERE id=:id";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->bindParam(':id', $contact['id']);
    $update_stmt->bindParam(':phone', $phone);
    $update_stmt->bindParam(':email', $email);
    $update_stmt->bindParam(':address', $address);
    $update_stmt->bindParam(':whatsapp', $whatsapp);
    
    if($update_stmt->execute()) {
        $success = "Contact information updated successfully!";
        // Refresh data
        $contact_stmt->execute();
        $contact = $contact_stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Info - Maison Admin</title>
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
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        
        .contact-form {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            max-width: 600px;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        .form-group textarea {
            resize: vertical;
        }
        
        .current-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 2rem;
        }
        
        .current-info h3 {
            font-weight: 400;
            margin-bottom: 1rem;
            color: #333;
        }
        
        .info-item {
            display: flex;
            margin-bottom: 0.5rem;
        }
        
        .info-label {
            font-weight: 500;
            width: 100px;
            color: #555;
        }
        
        .info-value {
            color: #333;
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
                <li><a href="products.php">Products</a></li>
                <li><a href="reviews.php">Reviews</a></li>
                <li><a href="gallery.php">Gallery</a></li>
                <li><a href="contact.php" class="active">Contact Info</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Contact Information</h1>
            </div>
            
            <?php if(isset($success)): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <!-- Current Contact Info Display -->
            <div class="current-info">
                <h3>Current Information</h3>
                <div class="info-item">
                    <span class="info-label">Phone:</span>
                    <span class="info-value"><?php echo htmlspecialchars($contact['phone']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?php echo htmlspecialchars($contact['email']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Address:</span>
                    <span class="info-value"><?php echo htmlspecialchars($contact['address']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">WhatsApp:</span>
                    <span class="info-value"><?php echo htmlspecialchars($contact['whatsapp']); ?></span>
                </div>
            </div>
            
            <!-- Edit Form -->
            <div class="contact-form">
                <h3 style="margin-bottom: 1.5rem; font-weight: 400;">Update Contact Information</h3>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($contact['phone']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($contact['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" rows="3" required><?php echo htmlspecialchars($contact['address']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>WhatsApp Number (with country code)</label>
                        <input type="text" name="whatsapp" value="<?php echo htmlspecialchars($contact['whatsapp']); ?>" required>
                        <small style="color: #666;">Example: +1234567890</small>
                    </div>
                    
                    <button type="submit" class="btn">Update Information</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>