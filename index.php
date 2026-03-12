<?php
session_start();
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

/* Featured Products */
$featured_query = "SELECT * FROM products WHERE featured = 1 LIMIT 4";
$featured_stmt = $db->prepare($featured_query);
$featured_stmt->execute();
$featured_products = $featured_stmt->fetchAll(PDO::FETCH_ASSOC);

/* Reviews */
$reviews_query = "SELECT * FROM reviews WHERE is_featured = 1 ORDER BY id DESC LIMIT 6";
$reviews_stmt = $db->prepare($reviews_query);
$reviews_stmt->execute();
$reviews = $reviews_stmt->fetchAll(PDO::FETCH_ASSOC);

/* Contact */
$contact_query = "SELECT * FROM contact_info LIMIT 1";
$contact_stmt = $db->prepare($contact_query);
$contact_stmt->execute();
$contact = $contact_stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">

<title>Maison Store</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto;
}

body{
background:#fafafa;
color:#111;
}

/* NAVBAR */

.navbar{
display:flex;
justify-content:space-between;
align-items:center;
padding:20px 5%;
background:#fff;
border-bottom:1px solid #eee;
}

.logo{
font-size:20px;
font-weight:600;
}

.nav-links{
display:flex;
gap:35px;
align-items:center;
}

.nav-links a{
text-decoration:none;
color:#444;
font-size:14px;
}

.whatsapp-btn{
background:#e8f8ee;
padding:8px 18px;
border-radius:20px;
color:#1fa855;
text-decoration:none;
}

/* HERO */

.hero{
display:flex;
justify-content:space-between;
align-items:center;
padding:80px 5%;
margin:30px 5%;
background:linear-gradient(90deg,#f7f7ff,#f2fff7);
border-radius:20px;
}

.hero-text{
max-width:520px;
}

.hero-label{
color:#7a6df0;
font-size:12px;
letter-spacing:2px;
margin-bottom:10px;
}

.hero h1{
font-size:48px;
margin-bottom:20px;
}

.hero p{
color:#555;
margin-bottom:25px;
}

.hero-buttons{
display:flex;
gap:15px;
}

.btn{
padding:12px 25px;
border-radius:25px;
text-decoration:none;
font-size:14px;
}

.btn-primary{
background:#7a6df0;
color:#fff;
}

.btn-outline{
border:1px solid #ccc;
color:#444;
}

.hero img{
width:400px;
border-radius:20px;
}

/* PRODUCTS */

.products-section{
padding:70px 5%;
}

.section-title{
margin-bottom:30px;
font-size:28px;
}

.products-grid{
display:grid;
grid-template-columns:repeat(4,1fr);
gap:25px;
}

.product-card{
background:#fff;
border-radius:18px;
overflow:hidden;
box-shadow:0 5px 20px rgba(0,0,0,0.05);
transition:.3s;
}

.product-card:hover{
transform:translateY(-6px);
}

.product-image{
position:relative;
height:200px;
}

.product-image img{
width:100%;
height:100%;
object-fit:cover;
}

.featured{
position:absolute;
top:10px;
left:10px;
background:#7a6df0;
color:#fff;
font-size:11px;
padding:4px 10px;
border-radius:12px;
}

.product-info{
padding:16px;
}

.product-meta{
display:flex;
justify-content:space-between;
font-size:12px;
margin-bottom:5px;
}

.product-name{
font-size:16px;
margin:5px 0;
}

.product-price{
font-size:18px;
font-weight:600;
}

.product-actions{
display:flex;
justify-content:space-between;
margin-top:10px;
}

.add-btn{
background:#7a6df0;
color:#fff;
border:none;
padding:6px 14px;
border-radius:10px;
cursor:pointer;
}

/* REVIEWS */

.reviews-section{
padding:70px 5%;
background:#f8f8f8;
}

.reviews-grid{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:25px;
}

.review-card{
background:#fff;
padding:25px;
border-radius:18px;
box-shadow:0 5px 20px rgba(0,0,0,0.05);
}

.review-stars{
font-size:18px;
margin-bottom:10px;
}

.review-text{
color:#555;
margin-bottom:15px;
line-height:1.5;
font-style:italic;
}

.review-author{
font-size:14px;
font-weight:500;
}

/* CATEGORY */

.category-section{
padding:70px 5%;
}

.category-grid{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:25px;
}

.category-card{
padding:40px;
border-radius:18px;
background:#f6f4ff;
}

.category-card h3{
margin-bottom:5px;
}

/* CONTACT */

.contact-section{
padding:70px 5%;
background:#f8f8f8;
display:grid;
grid-template-columns:1fr 1fr;
gap:40px;
}

.contact-info h3{
margin-bottom:20px;
}

.contact-form input,
.contact-form textarea{
width:100%;
padding:12px;
margin-bottom:12px;
border:1px solid #ddd;
border-radius:6px;
}

.contact-form button{
padding:12px 20px;
background:#7a6df0;
border:none;
color:#fff;
border-radius:6px;
cursor:pointer;
}

/* FOOTER */

.footer{
padding:30px;
text-align:center;
border-top:1px solid #eee;
color:#777;
}

/* MOBILE */

@media(max-width:1000px){

.hero{
flex-direction:column;
gap:30px;
}

.products-grid{
grid-template-columns:repeat(2,1fr);
}

.reviews-grid{
grid-template-columns:1fr;
}

.category-grid{
grid-template-columns:1fr;
}

.contact-section{
grid-template-columns:1fr;
}

}

</style>

</head>

<body>

<!-- NAVBAR -->

<div class="navbar">

<div class="logo">Maison</div>

<div class="nav-links">

<a href="#">Home</a>
<a href="shop.php">Shop</a>
<a href="#reviews">Reviews</a>
<a href="#contact">Contact</a>

<a class="whatsapp-btn"
href="https://wa.me/<?php echo $contact['whatsapp']; ?>">
WhatsApp
</a>

</div>

</div>


<!-- HERO -->

<div class="hero">

<div class="hero-text">

<div class="hero-label">NEW COLLECTION 2026</div>

<h1>Curated for<br>Modern Living</h1>

<p>
Discover thoughtfully designed products that blend
simplicity with elegance for everyday life.
</p>

<div class="hero-buttons">

<a class="btn btn-primary" href="shop.php">Shop Now</a>

<a class="btn btn-outline"
href="https://wa.me/<?php echo $contact['whatsapp']; ?>">
Order on WhatsApp
</a>

</div>

</div>

<img src="uploads/hero.jpg">

</div>


<!-- FEATURED PRODUCTS -->

<div class="products-section">

<h2 class="section-title">Featured Products</h2>

<div class="products-grid">

<?php foreach($featured_products as $product): ?>

<div class="product-card">

<div class="product-image">

<span class="featured">Featured</span>

<img src="uploads/<?php echo $product['image']; ?>">

</div>

<div class="product-info">

<div class="product-meta">

<span><?php echo $product['category']; ?></span>

<span>⭐ <?php echo $product['rating']; ?></span>

</div>

<div class="product-name">
<?php echo $product['name']; ?>
</div>

<div class="product-price">
$<?php echo number_format($product['price'],2); ?>
</div>

<div class="product-actions">

<a class="whatsapp-btn"
href="https://wa.me/<?php echo $contact['whatsapp']; ?>?text=I want <?php echo urlencode($product['name']); ?>">
💬
</a>

<button class="add-btn">Add</button>

</div>

</div>

</div>

<?php endforeach; ?>

</div>

</div>


<!-- REVIEWS -->

<div class="reviews-section" id="reviews">

<h2 class="section-title">What Our Customers Say</h2>

<div class="reviews-grid">

<?php foreach($reviews as $review): ?>

<div class="review-card">

<div class="review-stars">
<?php echo str_repeat("⭐",$review['rating']); ?>
</div>

<p class="review-text">
"<?php echo htmlspecialchars($review['comment']); ?>"
</p>

<div class="review-author">
<strong><?php echo $review['customer_initial']; ?></strong>
<?php echo htmlspecialchars($review['customer_name']); ?>
</div>

</div>

<?php endforeach; ?>

</div>

</div>


<!-- CATEGORY -->

<div class="category-section">

<h2 class="section-title">Shop by Category</h2>

<div class="category-grid">

<div class="category-card">
<h3>New Arrivals</h3>
<p>Explore collection →</p>
</div>

<div class="category-card" style="background:#fff5f2;">
<h3>Best Sellers</h3>
<p>Explore collection →</p>
</div>

<div class="category-card" style="background:#f1fff7;">
<h3>Special Offers</h3>
<p>Explore collection →</p>
</div>

</div>

</div>


<!-- CONTACT -->

<div class="contact-section" id="contact">

<div class="contact-info">

<h3>Contact Us</h3>

<p><?php echo $contact['address']; ?></p>

<p>Phone: <?php echo $contact['phone']; ?></p>

<p>Email: <?php echo $contact['email']; ?></p>

</div>

<div class="contact-form">

<form action="send_message.php" method="POST">

<input type="text" name="name" placeholder="Your Name" required>

<input type="email" name="email" placeholder="Email" required>

<textarea name="message" rows="5" placeholder="Message"></textarea>

<button type="submit">Send Message</button>

</form>

</div>

</div>


<!-- FOOTER -->

<div class="footer">

© 2026 Maison Store

</div>

</body>
</html>