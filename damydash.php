<?php
include 'db.php';
session_start();

if(!isset($_SESSION['user_id'])) header("Location: login.php");

$shop_name = $_GET['shop'] ?? 'Unknown Shop';
$price = $_GET['price'] ?? '0';
$post_id = $_GET['post_id'] ?? '0';
$date = date("Y-m-d H:i:s");

// Optional: Update database status to 'collected' or 'accepted'
if($post_id != '0') {
 $conn->query("UPDATE waste_posts SET status='accepted' WHERE id='$post_id'");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>E-Waste Pickup Invoice</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .invoice-box {
            background: #fff;
            padding: 40px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            max-width: 600px;
            margin: auto;
        }
        .print-btn { background: #34495e; margin-top: 20px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="invoice-box">
            <h1 style="color: #27ae60;">Deal Confirmed!</h1>
            <hr>
            <p><strong>Receipt ID:</strong> #EW-<?php echo rand(1000, 9999); ?></p>
            <p><strong>Date:</strong> <?php echo $date; ?></p>
            <p><strong>Customer:</strong> <?php echo $_SESSION['username']; ?></p>
            <hr>
            <h3>Pickup Details</h3>
            <p><strong>Shop:</strong> <?php echo htmlspecialchars($shop_name); ?></p>
            <p><strong>Total Offer:</strong> <span style="font-size: 1.2rem; color: #27ae60;">₹<?php echo $price; ?></span></p>
            <p><em>Please keep your e-waste ready for pickup. The shop will contact you at your registered details.</em></p>
            
            <button class="btn print-btn no-print" onclick="window.print()">Save as PDF / Print</button>
            <a href="dashboard.php" class="no-print" style="display:block; margin-top:20px; color: #777;">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>