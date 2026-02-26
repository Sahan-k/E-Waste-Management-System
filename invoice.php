<?php
include 'db.php';

session_start();

if(!isset($_SESSION['user_id'])) header("Location: login.php");

$shop_name = $_GET['shop'] ?? 'Unknown Shop';
$price = $_GET['price'] ?? '0';
$post_id = $_GET['post_id'] ?? '0';
$date = date("Y-m-d H:i:s");

// UPDATE DATABASE: Lock in the price and set status to accepted
if($post_id != '0') {
    $update_sql = "UPDATE waste_posts 
                   SET status='accepted', price_offer='$price' 
                   WHERE id='$post_id'";
    $conn->query($update_sql);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>E-Waste Pickup Invoice</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .invoice-box { background: #fff; padding: 40px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); max-width: 600px; margin: 50px auto; border-radius: 15px; }
        .print-btn { background: #34495e; margin-top: 20px; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="invoice-box">
        <h1 style="color: #27ae60;">Deal Confirmed!</h1>
        <hr>
        <p><strong>Receipt ID:</strong> #EW-<?php echo rand(1000, 9999); ?></p>
        <p><strong>Date:</strong> <?php echo $date; ?></p>
        <p><strong>Customer:</strong> <?php echo $_SESSION['username']; ?></p>
        <hr>
        <h3>Pickup Details</h3>
        <p><strong>Shop:</strong> <?php echo htmlspecialchars($shop_name); ?></p>
        <p><strong>Total Offer:</strong> <span style="font-size: 1.5rem; color: #27ae60; font-weight: bold;">₹<?php echo number_format($price, 2); ?></span></p>
        <p><em>Keep your e-waste ready. The shop will contact you shortly.</em></p>
        
        <button class="print-btn no-print" onclick="window.print()">Print Receipt</button>
        <a href="dashboard.php" class="no-print" style="display:block; margin-top:20px; color: #27ae60; text-decoration: none; font-weight: bold;">← Back to Dashboard</a>
    </div>
</body>
</html>