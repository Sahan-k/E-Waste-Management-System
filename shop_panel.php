<?php
include 'db.php';
session_start();

// 1. Security Check: Only Shop Keepers allowed
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'shop') {
    header("Location: login.php");
    exit();
}

$shop_id = $_SESSION['user_id'];
$shop_name = $_SESSION['username'];

// 2. Handle Price Submission
if(isset($_POST['submit_price'])) {
    $post_id = $_POST['post_id'];
    $price = $_POST['price'];
    
    // Update the database: set the price, assign the current shop_id, and change status to 'offered'
    $stmt = $conn->prepare("UPDATE waste_posts SET price_offer = ?, shop_id = ?, status = 'offered' WHERE id = ?");
    $stmt->bind_param("dii", $price, $shop_id, $post_id);
    
    if($stmt->execute()) {
        echo "<script>alert('Offer of ₹$price sent successfully!'); window.location='shop_panel.php';</script>";
    }
}

// 3. FETCH LOGIC: 
// Show posts that are 'pending' (new for everyone) 
// OR posts where THIS specific shop has already made an offer (offered or accepted)
$posts = $conn->query("SELECT waste_posts.*, users.username FROM waste_posts 
                       JOIN users ON waste_posts.user_id = users.id 
                       WHERE waste_posts.status = 'pending' 
                       OR waste_posts.shop_id = '$shop_id'
                       ORDER BY FIELD(status, 'accepted', 'offered', 'pending'), id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Panel | E-Waste Connect</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Specific Styles for Shop Cards */
        .post-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-top: 30px;
        }

        .post-card {
            display: flex;
            align-items: center;
            gap: 25px;
            transition: transform 0.3s ease;
        }

        .post-card:hover {
            transform: translateY(-5px);
            border-color: var(--accent-purple) !important;
        }

        .waste-img {
            width: 150px;
            height: 150px;
            border-radius: 20px;
            object-fit: cover;
            border: 1px solid var(--border-glass);
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-pending { background: rgba(255, 193, 7, 0.1); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.2); }
        .status-offered { background: rgba(112, 71, 235, 0.1); color: #7047eb; border: 1px solid rgba(112, 71, 235, 0.2); }
        .status-accepted { background: rgba(0, 224, 176, 0.1); color: #00e0b0; border: 1px solid rgba(0, 224, 176, 0.2); }

        .offer-input {
            flex: 1;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-glass);
            color: black;
            padding: 12px;
            border-radius: 12px;
            outline: none;
        }

        .offer-input:focus {
            border-color: var(--accent-purple);
            background: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="margin: 0;">Marketplace</h1>
                <p style="color: var(--text-dim);">Find and offer prices for local e-waste</p>
            </div>
            <div class="stat-card" style="padding: 15px 25px;">
                <small style="color: var(--text-dim); display: block;">Shop Authenticated</small>
                <span style="color: var(--accent-green); font-weight: 800;">● Online</span>
            </div>
        </div>

        <div class="post-container">
            <?php if($posts->num_rows > 0): ?>
                <?php while($row = $posts->fetch_assoc()): ?>
                    <div class="card post-card">
                        <img src="<?php echo $row['image_path']; ?>" class="waste-img" alt="E-waste">
                        
                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div>
                                    <h3 style="margin: 0; font-size: 1.4rem;"><?php echo htmlspecialchars($row['description']); ?></h3>
                                    <p style="color: var(--text-dim); margin: 5px 0;">Customer: <b><?php echo $row['username']; ?></b></p>
                                </div>
                                <span class="status-badge status-<?php echo $row['status']; ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </div>

                            <div style="margin-top: 20px;">
                                <?php 
                                // CASE 1: New Item - Show the bidding form
                                if($row['status'] == 'pending'): ?>
                                    <form method="POST" style="display: flex; gap: 12px;">
                                        <input type="hidden" name="post_id" value="<?php echo $row['id']; ?>">
                                        <input type="number" name="price" class="offer-input" placeholder="Enter your best price (₹)" required>
                                        <button type="submit" name="submit_price">Send Offer</button>
                                    </form>

                                <?php 
                                // CASE 2: You made an offer, waiting for customer to click 'Accept'
                                elseif($row['status'] == 'offered' && $row['shop_id'] == $shop_id): ?>
                                    <div style="background: rgba(112, 71, 235, 0.05); border: 1px dashed var(--accent-purple); padding: 15px; border-radius: 15px; color: var(--accent-purple); display: flex; align-items: center; gap: 10px;">
                                        <span style="font-size: 1.2rem;">⏳</span>
                                        <span>Your Offer of <b>₹<?php echo number_format($row['price_offer'], 2); ?></b> is pending user approval.</span>
                                    </div>

                                <?php 
                                // CASE 3: Deal WON! User accepted your specific offer
                                elseif($row['status'] == 'accepted' && $row['shop_id'] == $shop_id): ?>
                                    <div style="background: rgba(0, 224, 176, 0.1); border: 1px solid var(--accent-green); padding: 15px; border-radius: 15px; color: var(--accent-green); display: flex; align-items: center; gap: 10px;">
                                        <span style="font-size: 1.2rem;">🎉</span>
                                        <div>
                                            <b>Deal Secured!</b> You bought this for ₹<?php echo number_format($row['price_offer'], 2); ?>.
                                            <br><small style="opacity: 0.8;">The customer has been notified to visit your shop.</small>
                                        </div>
                                    </div>

                                <?php 
                                // CASE 4: Security fall-back (if someone else got the deal)
                                else: ?>
                                    <div style="opacity: 0.4; font-style: italic;">
                                        This item is no longer available for bidding.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="card" style="text-align: center; padding: 60px;">
                    <span style="font-size: 3rem; display: block; margin-bottom: 20px;">🔍</span>
                    <h3 style="margin: 0; color: var(--text-dim);">No requests found</h3>
                    <p>Check back later for new e-waste postings in your area.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>