<?php
include 'db_config.php';
if(!$conn) { echo "DB Connection Failed!"; }
session_start();

// Login Logic
$error = "";
if(isset($_POST['login'])) {
    if($_POST['user'] == "admin" && $_POST['pass'] == "1234") { 
        $_SESSION['admin_logged'] = true;
    } else { $error = "Invalid Username or Password!"; }
}

if(isset($_GET['logout'])) { 
    session_destroy(); 
    header('location:admin.php'); 
}

// Handle Order Completion
if(isset($_GET['complete_id'])) {
    $id = intval($_GET['complete_id']);
    mysqli_query($conn, "DELETE FROM orders WHERE ID = $id");
    header('location:admin.php');
}

// Handle Add Product
if(isset($_POST['add_product'])) {
    $p_name = mysqli_real_escape_string($conn, $_POST['p_name']);
    $p_price = $_POST['p_price'];
    $p_category = $_POST['p_category'];
    
    $image_name = $_FILES['p_image']['name'];
    $image_tmp = $_FILES['p_image']['tmp_name'];
    $image_folder = "images/" . $image_name;

    $insert_query = "INSERT INTO products (name, price, image, category) VALUES ('$p_name', '$p_price', '$image_name', '$p_category')";

    if(mysqli_query($conn, $insert_query)) {
        move_uploaded_file($image_tmp, $image_folder);
        echo "<script>alert('Product Added Successfully!'); window.location.href='admin.php';</script>";
    }
}

// Handle Product Deletion
if(isset($_GET['delete_product'])) {
    $p_id = intval($_GET['delete_product']);
    mysqli_query($conn, "DELETE FROM products WHERE id = $p_id");
    header('location:admin.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Flavor Hub | Admin Portal</title>
<style>
* { margin:0; padding:0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
body { display: flex; height: 100vh; background: #f8f9fa; overflow: hidden; }
.sidebar { width: 280px; background: #0f172a; color: white; display: flex; flex-direction: column; }
.sidebar .brand { padding: 40px 30px; border-bottom: 1px solid #1e293b; }
.sidebar h2 { color: #f97316; font-size: 24px; }
.nav-menu { list-style: none; padding: 20px 0; flex: 1; }
.nav-item { padding: 18px 30px; cursor: pointer; color: #94a3b8; transition: 0.3s; }
.nav-item:hover, .nav-item.active { background: #1e293b; color: white; border-left: 4px solid #f97316; }
.main-content { flex: 1; padding: 40px; overflow-y: auto; }
.page-content { display: none; }
.page-content.active { display: block; }
.welcome-container { height: 80vh; display: flex; justify-content: center; align-items: center; text-align: center; }
.welcome-text { font-size: 80px; font-weight: 800; color: #1e293b; }
.welcome-subtext { font-size: 20px; color: #f97316; letter-spacing: 2px; }
.header-text { font-size: 28px; font-weight: 700; margin-bottom: 30px; color: #1e293b; }
.card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); border: 1px solid #edf2f7; margin-bottom: 20px; }
/* Stats Container */
.stats-container { display: flex; gap: 20px; margin-bottom: 30px; }
.stat-box { flex: 1; background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); border: 1px solid #edf2f7; text-align: center; }
.stat-box h3 { font-size: 14px; color: #64748b; text-transform: uppercase; margin-bottom: 10px; }
.stat-box p { font-size: 32px; font-weight: 700; color: #f97316; }
table { width: 100%; border-collapse: collapse; }
th { text-align: left; padding: 15px; background: #f1f5f9; color: #475569; font-size: 12px; text-transform: uppercase; }
td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
.btn-action { color: #ef4444; text-decoration: none; font-weight: 600; border: 1px solid #ef4444; padding: 5px 10px; border-radius: 5px; }
.login-screen { width: 100%; display: flex; justify-content: center; align-items: center; background: #0f172a; height: 100vh;}
.login-card { width: 380px; background: white; padding: 40px; border-radius: 16px; text-align: center; }
input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #e2e8f0; border-radius: 8px; }
.btn-primary { background: #f97316; color: white; border: none; padding: 14px; border-radius: 8px; cursor: pointer; width: 100%; font-weight: 600; }
</style>
</head>
<body>

<?php if(!isset($_SESSION['admin_logged'])): ?>
<div class="login-screen">
    <div class="login-card">
        <h2 style="color:#f97316; margin-bottom:20px;">Flavor Hub</h2>
        <form method="POST">
            <input type="text" name="user" placeholder="Username" required>
            <input type="password" name="pass" placeholder="Password" required>
            <button type="submit" name="login" class="btn-primary">Login</button>
        </form>
        <?php if($error) echo "<p style='color:red;margin-top:15px;'>$error</p>"; ?>
    </div>
</div>

<?php else: ?>
<div class="sidebar">
    <div class="brand">
        <h2>Flavor Hub</h2>
        <p style="letter-spacing:3px;font-size:10px;color:#64748b;">ADMIN PORTAL</p>
    </div>
    <ul class="nav-menu">
        <li class="nav-item active" id="nav-home" onclick="showPage('home')">Dashboard</li>
        <li class="nav-item" id="nav-orders" onclick="showPage('orders')">Manage Orders</li>
        <li class="nav-item" id="nav-add" onclick="showPage('add-item')">Manage Menu</li>
        <li class="nav-item" onclick="window.location.href='admin.php?logout=1'" style="color:#f87171;margin-top:50px;">Log Out</li>
    </ul>
</div>

<div class="main-content">
    <div id="home" class="page-content active">
        <div class="header-text" style="margin-bottom: 20px;">Dashboard</div>
        
        <div class="stats-container">
            <div class="stat-box">
                <h3>Total Orders</h3>
                <?php
                $order_count_query = "SELECT COUNT(*) AS total_orders FROM orders";
                $order_count_result = mysqli_query($conn, $order_count_query);
                $row_order = mysqli_fetch_assoc($order_count_result);
                ?>
                <p><?php echo $row_order['total_orders']; ?></p>
            </div>
            
            <div class="stat-box">
                <h3>Total Menu Items</h3>
                <?php
                $prod_count_query = "SELECT COUNT(*) AS total_products FROM products";
                $prod_count_result = mysqli_query($conn, $prod_count_query);
                $row_prod = mysqli_fetch_assoc($prod_count_result);
                ?>
                <p><?php echo $row_prod['total_products']; ?></p>
            </div>
            
            <div class="stat-box">
                <h3>Total Pending Orders</h3>
                <?php
                $pending_count_query = "SELECT COUNT(*) AS total_pending FROM orders";
                $pending_count_result = mysqli_query($conn, $pending_count_query);
                $row_pending = mysqli_fetch_assoc($pending_count_result);
                ?>
                <p><?php echo $row_pending['total_pending']; ?></p>
            </div>
        </div>

        <div class="welcome-container" style="height: 60vh;">
            <div>
                <h1 class="welcome-text">Welcome</h1>
                <p class="welcome-subtext">Administrator Control Panel</p>
            </div>
        </div>
    </div>

    <div id="orders" class="page-content">
        <h1 class="header-text">Orders</h1>
        <div class="card" style="padding:0;">
            <table>
                <thead>
                    <tr><th>ID</th><th>CUSTOMER</th><th>ADDRESS</th><th>CONTACT</th><th>ITEM</th><th>PAYMENT</th><th>DATE</th><th>ACTION</th></tr>
                </thead>
                <tbody>
                    <?php
                    $order_query = "SELECT * FROM orders ORDER BY ID DESC";
                    $result = mysqli_query($conn,$order_query);
                    if($result && mysqli_num_rows($result)>0){
                        while($row = mysqli_fetch_assoc($result)){
                    ?>
                    <tr>
                        <td><?php echo $row['ID']; ?></td>
                        <td><?php echo $row['CUSTOMER']; ?></td>
                        <td><?php echo $row['ADDRESS']; ?></td>
                        <td><?php echo $row['CONTACT']; ?></td>
                        <td><?php echo $row['ITEM']; ?></td>
                        <td><?php echo strtoupper($row['PAYMENT']); ?></td>
                        <td><?php echo $row['ORDER_DATE']; ?></td>
                        <td><a href="admin.php?complete_id=<?php echo $row['ID']; ?>" class="btn-action" onclick="return confirm('Complete this order?')">Done</a></td>
                    </tr>
                    <?php } } else { echo "<tr><td colspan='8' style='text-align:center;padding:50px;color:#94a3b8;'>No pending orders found.</td></tr>"; } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="add-item" class="page-content">
        <h1 class="header-text">Add New Product</h1>
        <div class="card">
            <form action="admin.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="p_name" placeholder="Product Name" required>
                <input type="number" name="p_price" placeholder="Price (LKR)" required>
                <select name="p_category" style="width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <option value="Breakfast">Breakfast</option>
                    <option value="Lunch">Lunch</option>
                    <option value="Dinner">Dinner</option>
                    <option value="Beverages">Beverages</option>
                </select>
                <input type="file" name="p_image" accept="image/*" required>
                <button type="submit" name="add_product" class="btn-primary" style="margin-top: 20px;">Upload Product</button>
            </form>
        </div>

        <h1 class="header-text" style="margin-top: 50px;">Menu List</h1>
        <div class="card" style="padding:0;">
            <table>
                <thead>
                    <tr><th>IMAGE</th><th>NAME</th><th>PRICE</th><th>CATEGORY</th><th>ACTION</th></tr>
                </thead>
                <tbody>
                    <?php
                    $prod_query = "SELECT * FROM products ORDER BY id DESC";
                    $prod_result = mysqli_query($conn, $prod_query);
                    if($prod_result && mysqli_num_rows($prod_result) > 0){
                        while($p_row = mysqli_fetch_assoc($prod_result)){
                    ?>
                    <tr>
                        <td><img src="images/<?php echo $p_row['image']; ?>" width="50" style="border-radius:5px;"></td>
                        <td><?php echo $p_row['name']; ?></td>
                        <td>LKR <?php echo $p_row['price']; ?></td>
                        <td><?php echo $p_row['category']; ?></td>
                        <td><a href="admin.php?delete_product=<?php echo $p_row['id']; ?>" class="btn-action" onclick="return confirm('Delete this item?')">Delete</a></td>
                    </tr>
                    <?php } } else { echo "<tr><td colspan='5' style='text-align:center;padding:20px;'>No items found.</td></tr>"; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function showPage(pageId){
    document.querySelectorAll('.page-content').forEach(p=>p.classList.remove('active'));
    document.querySelectorAll('.nav-item').forEach(i=>i.classList.remove('active'));
    document.getElementById(pageId).classList.add('active');
    if(pageId==='home') document.getElementById('nav-home').classList.add('active');
    if(pageId==='orders') document.getElementById('nav-orders').classList.add('active');
    if(pageId==='add-item') document.getElementById('nav-add').classList.add('active');
}
</script>
<?php endif; ?>
</body>
</html>