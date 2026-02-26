<?php
include 'db.php';
session_start();

// Security check
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Query updated to include 'w.id' so the dashboard knows which post to accept
$sql = "SELECT 
            u.username AS shop_name, 
            u.lat, 
            u.lng, 
            w.price_offer AS price,
            w.id AS post_id
        FROM waste_posts w
        JOIN users u ON w.shop_id = u.id 
        WHERE w.user_id = '$user_id' AND w.status = 'offered'";

$result = $conn->query($sql);
$offers = [];

if ($result) {
    while($row = $result->fetch_assoc()) {
        $row['lat'] = (float)$row['lat'];
        $row['lng'] = (float)$row['lng'];
        $offers[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($offers);
?>