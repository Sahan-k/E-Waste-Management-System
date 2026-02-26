<?php 
include 'db.php';
 
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// CALCULATE EARNINGS: Only count items where status is 'accepted'
$cash_query = "SELECT SUM(price_offer) AS total FROM waste_posts WHERE user_id = '$user_id' AND status = 'accepted'";
$cash_result = $conn->query($cash_query);
$cash_row = $cash_result->fetch_assoc();
$total_earned = $cash_row['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard | E-Waste Connect</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="style.css">
    <style>
        #map { height: 400px; width: 100%; border-radius: 20px; margin-top: 15px; border: 4px solid white; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 25px; border-radius: 20px; text-align: center; box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .card-icon { font-size: 2rem; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <!--<header class="navbar">
       
        <div class="logo">♻️ <span>E-Waste <b>Connect</b></span></div>
        <div class="nav-links">
            <span style="margin-right:20px">👋 Hi, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b></span>
            <a href="profile.php" style="margin-right: 15px; color: #27ae60; text-decoration: none;">👤 Profile</a>
            <a href="logout.php" class="btn" style="background:#e74c3c; color: white; text-decoration: none;">Logout</a>
        </div>
    </header>-->

    <div class="container" style="margin-top: 30px;">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="card-icon">🌱</div>
                <h3>Recycle Mode</h3>
                <p>Active</p>
            </div>
            <div class="stat-card">
                <div class="card-icon">💰</div>
                <h3>Cash Earned</h3>
                <p style="font-size: 1.8rem; font-weight: 800; color: #27ae60; margin: 5px 0;">₹<?php echo number_format($total_earned, 2); ?></p>
                <small style="color: #7f8c8d;">From accepted deals</small>
            </div>
        </div>

        <div class="card">
            <h2>Post New E-Waste</h2>
            <form id="wasteForm" enctype="multipart/form-data">
                <input type="text" name="description" placeholder="What are you recycling?" required style="width: 100%; padding: 10px; margin: 10px 0;">
                <input type="file" name="waste_image" accept="image/*" required style="margin-bottom: 10px;">
                <button type="submit" style="width: 100%; background: #27ae60; color: white; padding: 12px; border: none; border-radius: 10px; cursor: pointer;">Post Item</button>
            </form>
        </div>

        <div class="card" style="margin-top: 20px;">
            <h2>Live Pickup Map</h2>
            <div id="map"></div>
        </div>
    </div>


    
    

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([12.87, 74.88], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        document.getElementById('wasteForm').onsubmit = async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const res = await fetch('upload.php', { method: 'POST', body: formData });
            const data = await res.json();
            if(data.success) { alert("Posted successfully!"); location.reload(); }
        };

        function fetchOffers() {
            fetch('get_offers.php').then(res => res.json()).then(offers => {
                offers.forEach(offer => {
                    L.marker([offer.lat, offer.lng]).addTo(map)
                    .bindPopup(`<b>${offer.shop_name}</b><br>Offer: ₹${offer.price}<br><button onclick="acceptDeal('${offer.shop_name}', ${offer.price}, ${offer.post_id})" style="background:#27ae60; color:white; border:none; padding:5px 10px; border-radius:5px; cursor:pointer; margin-top:5px;">Accept</button>`);
                });
            });
        }

        function acceptDeal(shop, price, id) {
            if(confirm(`Accept ₹${price} from ${shop}?`)) {
                window.location.href = `invoice.php?shop=${encodeURIComponent(shop)}&price=${price}&post_id=${id}`;
            }
        }
        fetchOffers();
    </script>
</body>
</html>