<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";
$msg_type = "";

// Fetch current user details
$user_query = $conn->query("SELECT * FROM users WHERE id = '$user_id'");
$user = $user_query->fetch_assoc();

if (isset($_POST['update_profile'])) {
    $new_password = mysqli_real_escape_string($conn, $_POST['password']);
    $lat = isset($_POST['lat']) ? $_POST['lat'] : $user['lat'];
    $lng = isset($_POST['lng']) ? $_POST['lng'] : $user['lng'];

    // Update Query
    $update_sql = "UPDATE users SET password = '$new_password', lat = $lat, lng = $lng WHERE id = '$user_id'";
    
    if ($conn->query($update_sql)) {
        $msg = "Profile updated successfully!";
        $msg_type = "success";
        // Refresh local data
        $user['password'] = $new_password;
    } else {
        $msg = "Error updating profile.";
        $msg_type = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | E-Waste Connect</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f4f7f6; margin: 0; padding: 20px; display: flex; justify-content: center; }
        .profile-card { background: white; padding: 40px; border-radius: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); width: 100%; max-width: 500px; }
        .nav-link { display: inline-block; margin-bottom: 20px; color: #2ecc71; text-decoration: none; font-weight: bold; }
        input, select { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 10px; }
        .btn { width: 100%; padding: 14px; background: #2ecc71; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: bold; margin-top: 10px; }
        .msg { padding: 10px; border-radius: 8px; margin-bottom: 15px; text-align: center; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        #map { height: 250px; border-radius: 12px; margin: 10px 0; border: 1px solid #ddd; }
        .role-badge { display: inline-block; padding: 5px 15px; background: #e8f5e9; color: #2ecc71; border-radius: 20px; font-size: 0.8rem; font-weight: bold; text-transform: uppercase; }
    </style>
</head>
<body>

<div class="profile-card">
    <a href="javascript:history.back()" class="nav-link">← Back to Dashboard</a>
    
    <h2>Account Settings</h2>
    <div class="role-badge"><?php echo $user['role']; ?> Account</div>
    <p>Username: <strong><?php echo $user['username']; ?></strong></p>

    <?php if($msg): ?>
        <div class="msg <?php echo $msg_type; ?>"><?php echo $msg; ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Update Password:</label>
        <input type="text" name="password" value="<?php echo $user['password']; ?>" required>

        <?php if($user['role'] == 'shop'): ?>
            <label>Update Shop Location:</label>
            <div id="map"></div>
            <input type="hidden" name="lat" id="lat" value="<?php echo $user['lat']; ?>">
            <input type="hidden" name="lng" id="lng" value="<?php echo $user['lng']; ?>">
        <?php endif; ?>

        <button type="submit" name="update_profile" class="btn">Save Changes</button>
    </form>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    <?php if($user['role'] == 'shop'): ?>
    var currentLat = <?php echo $user['lat'] ? $user['lat'] : '12.9141'; ?>;
    var currentLng = <?php echo $user['lng'] ? $user['lng'] : '74.8560'; ?>;

    var map = L.map('map').setView([currentLat, currentLng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    var marker = L.marker([currentLat, currentLng], {draggable: true}).addTo(map);

    // Update hidden inputs when marker is moved
    marker.on('dragend', function(e) {
        var position = marker.getLatLng();
        document.getElementById('lat').value = position.lat;
        document.getElementById('lng').value = position.lng;
    });

    // Update marker and inputs when map is clicked
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        document.getElementById('lat').value = e.latlng.lat;
        document.getElementById('lng').value = e.latlng.lng;
    });
    <?php endif; ?>
</script>

</body>
</html>