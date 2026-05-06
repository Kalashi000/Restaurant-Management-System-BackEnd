<?php include 'db_config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flavor Hub | Fine Dining Experience</title>
    <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo">Flavor<span>Hub</span></div>
        <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#menu">Menu</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </nav>

    <header class="hero" id="home" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('images/bg.jpg') no-repeat center center/cover;">
    <div class="hero-content">
        <h1>Experience the Real Flavor</h1>
        <p>Indulge in a world of exquisite tastes and premium dining at Flavor Hub.</p>
        <a href="#menu" class="btn">Explore Our Menu</a>
    </div>
    </header>

    <section class="menu-filters" id="menu">
        <h2 class="section-title">Our Menu</h2> 
        <div class="underline"></div>

        <div class="search-container">
            <input type="text" id="menu-search" placeholder="Search for your favorite food...">
        </div>

        <div class="filter-tabs">
            <button class="filter-btn active" data-filter="all">All Items</button>
            <button class="filter-btn" data-filter="breakfast">Breakfast</button>
            <button class="filter-btn" data-filter="lunch">Lunch</button>
            <button class="filter-btn" data-filter="dinner">Dinner</button>
            <button class="filter-btn" data-filter="beverages">Beverages</button>
        </div>

    <div class="menu-grid" id="menu-grid">
    <?php
    // Fetch products from database
    $query = "SELECT * FROM products";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="food-card" data-category="<?php echo strtolower($row['category']); ?>">
                <div class="food-img">
                    <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                </div>
                <div class="food-content">
                    <h3><?php echo $row['name']; ?></h3>
                    <p><?php echo $row['description']; ?></p>
                    <div class="food-footer">
                        <span class="price">Rs. <?php echo number_format($row['price'], 2); ?></span>
                        <button class="order-btn">Order Now</button>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p>No items found in the menu.</p>";
    }
    ?>
</div>


    </section>

    <section class="about" id="about">
    <div class="about-container">
        <div class="about-text">
            <h2>About <span>Flavor Hub</span></h2>
            
            <p>
                Founded in <strong>2010</strong>, Flavor Hub has grown from a simple dream into a soulful <strong>sanctuary for food lovers</strong>. Over the years, we have dedicated ourselves to providing a unique dining experience where every guest feels at home. Our journey is fueled by a deep passion for creating memorable moments through the magic of authentic flavors and warm hospitality.
            </p>
            
            <p>
                At the heart of our kitchen, we firmly believe in the power of nature. That’s why we use only <strong>100% organic, farm-fresh ingredients</strong>, hand-selected to ensure the highest quality in every bite. Our chefs masterfully craft each dish to tell a captivating story that honors <strong>rich culinary traditions</strong> while embracing the excitement of <strong>modern innovation</strong>.
            </p>
        </div>
        
        <div class="about-image">
            <img src="images/about us.JPG" alt="Flavor Hub Interior">
        </div>
    </div>
</section>

 <section id="contact" class="contact-section" style="padding: 60px 0; background-color: #fbfbfc; font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">
    
    <h2 style="text-align: center; font-size: 2rem; color: #1e293b; margin-bottom: 50px; font-weight: 600; letter-spacing: -0.5px;">Contact Us</h2>

    <div class="contact-container" style="display: flex; justify-content: space-between; align-items: flex-start; max-width: 950px; margin: 0 auto; padding: 0 1px; gap: 50px;">
        
        <div class="contact-info" >
            <div class="info-item" style="margin-bottom: 25px;">
                <h3 style="color: #e67e22; font-size: 1rem; margin-bottom: 5px; font-weight: 600; text-transform: none;">Visit Us</h3>
                <p style="color: #475569; font-size: 0.95rem; margin: 0; line-height: 1.5;">123 Foodie Street, Colombo</p>
            </div>
            
            <div class="info-item">
                <h3 style="color: #e67e22; font-size: 1rem; margin-bottom: 5px; font-weight: 600; text-transform: none;">Call Us</h3>
                <p style="color: #475569; font-size: 0.95rem; margin: 0;">
                    <a href="tel:+94112345678" style="text-decoration: none; color: inherit; font-weight: 500;">+94 11 234 5678</a>
                </p>
            </div>
        </div>

        <div class="contact-form" style="flex: 1.5; width: 100%; max-width: 500px;">
    
    <form id="contact-form" action="https://formspree.io/f/your-email@example.com" method="POST" style="display: flex; flex-direction: column; gap: 12px;">
        
        <input type="text" name="name" placeholder="Name" required 
            style="width: 100%; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 0.9rem; color: #333; outline: none; background-color: #fff;">
        
        <input type="email" name="email" placeholder="Email" required 
            style="width: 100%; padding: 10px 15px; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 0.9rem; color: #333; outline: none; background-color: #fff;">
        
        <textarea name="message" placeholder="Message" required 
            style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 0.9rem; color: #333; height: 100px; resize: none; outline: none; background-color: #fff; line-height: 1.5;"></textarea>
        
        <button type="submit" 
            style="background-color: #e67e22; color: #ffffff; padding: 12px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 1rem; transition: background 0.2s ease; margin-top: 5px;">
            Send Message
        </button>
        
    </form> 
</div>

    </div>
</section>

<section id="checkout-section" style="display: none; padding: 80px 8%; background: #f9f9f9;">
        <h2 class="section-title">Complete Your Order</h2>
        <div class="underline"></div>
        
        <div style="max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <h3 id="selected-food" style="color: #2c3e50; margin-bottom: 20px; font-family: 'Poppins', sans-serif;">Item: </h3>
            
            <div style="display: grid; gap: 15px; margin-bottom: 20px;">
                <input type="text" id="cus-name" placeholder="Your Name" style="padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-family: inherit;">
                <input type="text" id="cus-address" placeholder="Delivery Address" style="padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-family: inherit;">
                <input type="text" id="cus-phone" placeholder="Phone Number" style="padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-family: inherit;">
            </div>

            <h4 style="font-family: 'Poppins', sans-serif; margin-bottom: 10px;">Choose Payment Method:</h4>
            <div style="margin: 15px 0; display: flex; gap: 20px; font-family: 'Poppins', sans-serif;">
                <label style="cursor: pointer;"><input type="radio" name="payment" value="card" checked style="accent-color: #e67e22;"> Card Payment 💳</label>
                <label style="cursor: pointer;"><input type="radio" name="payment" value="cod" style="accent-color: #e67e22;"> Cash on Delivery 💵</label>
            </div>

            <button onclick="placeFinalOrder()" class="btn" style="width: 100%; border: none; cursor: pointer; font-size: 1.1rem;">Confirm Order Now</button>
        </div>
    </section>
  
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-logo">Flavor<span>Hub</span></div>
            <p>&copy; 2026 Flavor Hub Restaurant. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>

    <div id="fake-payhere" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999; justify-content:center; align-items:center;">
        <div style="background:white; padding:20px; border-radius:10px; width:320px; text-align:center; font-family:sans-serif; position:relative;">
            <img src="https://www.payhere.lk/downloads/images/payhere_logo_dark.png" width="150" alt="PayHere">
            <h3 style="margin-top:20px; color:#333;">Card Payment</h3>
            <p id="pay-amount" style="font-weight:bold; color:#2c3e50; margin:10px 0;"></p>
            <input type="text" id="card-number" placeholder="Card Number" style="width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:5px;">
            <div style="display:flex; gap:10px;">
                <input type="text" id="card-expiry" placeholder="MM/YY" maxlength="5" style="width:50%; padding:10px; border:1px solid #ccc; border-radius:5px;">
                <input type="text" id="card-cvc" maxlength="3" placeholder="CVC" style="width:50%; padding:10px; border:1px solid #ccc; border-radius:5px;">
            </div>
            <button type="button" onclick="finishMockPayment()" style="width:100%; background:#27ae60; color:white; border:none; padding:12px; margin-top:20px; cursor:pointer; font-weight:bold; border-radius:5px;">Pay Now</button>
            <button type="button" onclick="closeMockPayment()" style="background:none; border:none; color:red; margin-top:10px; cursor:pointer;">Cancel</button>
        </div>
    </div>

<div id="custom-alert" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:10000; justify-content:center; align-items:center;">
    <div style="background:white; padding:25px 40px; border-radius:12px; width:450px; text-align:center; font-family:sans-serif; box-shadow: 0 5px 25px rgba(0,0,0,0.6); animation: popIn 0.3s ease;">
        
        <div style="font-size: 50px; color: #27ae60; margin-bottom: 10px;"></div>
        
        <h2 id="alert-title" style="color: #2c3e50; margin-top: 0; font-size: 22px;"></h2>
        
        <p id="alert-message" style="color:red ; margin-bottom: 20px; line-height: 1.5; font-size: 16px;"></p>
        
        <button onclick="closeCustomAlert()" style="background:#e67e22; color:white; border:none; padding:6px 25px; border-radius:5px; cursor:pointer; font-weight:bold; font-size: 16px; transition: 0.3s;">OK</button>
    </div>
</div>

<style>
@keyframes popIn {
    0% { transform: scale(0.5); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}
</style>
</body>
</html>