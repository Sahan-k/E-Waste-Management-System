<?php
include 'db.php';
$msg = "";

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = $_POST['role']; // Will be 'user' or 'shop'
    
    // Capture location for shops, set to NULL for regular users
    $lat = ($role == 'shop' && !empty($_POST['lat'])) ? $_POST['lat'] : "NULL";
    $lng = ($role == 'shop' && !empty($_POST['lng'])) ? $_POST['lng'] : "NULL";

    // Check if username already exists
    $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
    if ($check->num_rows > 0) {
        $msg = "Username already taken!";
    } else {
        // Insert new user with role and coordinates
        $sql = "INSERT INTO users (username, password, role, lat, lng) 
                VALUES ('$username', '$password', '$role', $lat, $lng)";
        
        if ($conn->query($sql)) {
            echo "<script>alert('Registration Successful! Please Login.'); window.location='login.php';</script>";
        } else {
            $msg = "Registration failed. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up | E-Waste Connect</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        
           
       @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap');

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
     background: 
        linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
        url('sahan.png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;

    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
    padding: 20px;
}

/* Glass Card */
 .reg-card {
    position: relative;
    width: 100%;
    max-width: 420px;
    padding: 45px 35px;
    border-radius: 25px;
    color: white;
    text-align: center;
    background: rgba(15, 20, 30, 0.95);
    backdrop-filter: blur(10px);
}
.reg-card::before {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: 25px;
    padding: 2px; /* Border thickness */

    background: linear-gradient(
        90deg,
        #00ffcc,
        #2ecc71,
        #00f0ff,
        #9b59b6,
        #00ffcc
    );
    pointer-events: none;


    background-size: 300% 300%;
    animation: borderMove 4s linear infinite;

    /* THIS makes only border visible */
    -webkit-mask: 
        linear-gradient(#000 0 0) content-box, 
        linear-gradient(#000 0 0);
    -webkit-mask-composite: xor;
            mask-composite: exclude;
}
@keyframes borderMove {
    0% { background-position: 0% 50%; }
    100% { background-position: 300% 50%; }
}



.reg-card:hover {
    transform: translateY(-5px);
}

/* Headings */
h2 {
    font-weight: 700;
    margin-bottom: 10px;
}

h2 span {
    color: #2ecc71;
}

/* Inputs */
input, select {
    width: 100%;
    padding: 14px 16px;
    margin: 12px 0;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.2);
    background: rgba(255,255,255,0.07);
    color: white;
    font-size: 0.95rem;
    outline: none;
    transition: 0.3s ease;
}

input::placeholder {
    color: rgba(255,255,255,0.6);
}

input:focus, select:focus {
    border-color: #2ecc71;
    box-shadow: 0 0 10px rgba(46, 204, 113, 0.5);
}

/* Button */
button {
    width: 100%;
    padding: 14px;
    margin-top: 15px;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    border: none;
    border-radius: 14px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s ease;
}

button:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 25px rgba(46, 204, 113, 0.4);
}

/* Map Section */
#map-section {
    display: none;
    margin-top: 15px;
    text-align: left;
}

#map {
    height: 220px;
    border-radius: 15px;
    border: 1px solid rgba(255,255,255,0.2);
    margin-bottom: 10px;
}

.msg {
    color: #ff6b6b;
    margin-bottom: 10px;
    font-size: 0.85rem;
}

/* Login Link */
a {
    color: #2ecc71;
    text-decoration: none;
    font-weight: 600;
}

a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>

    <div class="reg-card">
        <h2>Join <span>Connect</span></h2>
        <p style="color: #7f8c8d; margin-bottom: 25px;">Create your account to start recycling</p>

        <?php if($msg) echo "<p class='msg'>$msg</p>"; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Choose Username" required>
            <input type="password" name="password" placeholder="Create Password" required>
            
            <select name="role" id="roleSelect" onchange="toggleMap()" required>
                <option value="user">Regular User (Dispose Waste)</option>
                <option value="shop">Shop Keeper (Recycle Waste)</option>
            </select>

            <div id="map-section">
                <p style="font-size: 0.8rem; color: #2ecc71; font-weight: bold;">📍 Click your shop location on map:</p>
                <div id="map"></div>
                <input type="hidden" name="lat" id="lat">
                <input type="hidden" name="lng" id="lng">
            </div>

            <button type="submit" name="register">Register Now</button>
        </form>

        <p style="margin-top:20px; font-size: 0.9rem;">
            Already have an account? <a href="login.php" style="color: #2ecc71; text-decoration: none; font-weight: bold;">Login here</a>
        </p>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map, marker;

        function toggleMap() {
            const role = document.getElementById('roleSelect').value;
            const mapSection = document.getElementById('map-section');
            
            if(role === 'shop') {
                mapSection.style.display = 'block';
                // Delay map initialization slightly to ensure it renders correctly
                if(!map) {
                    setTimeout(() => {
                        map = L.map('map').setView([12.9141, 74.8560], 12); // Default center
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                        
                        map.on('click', function(e) {
                            if(marker) map.removeLayer(marker);
                            marker = L.marker(e.latlng).addTo(map);
                            document.getElementById('lat').value = e.latlng.lat;
                            document.getElementById('lng').value = e.latlng.lng;
                        });
                    }, 100);
                }
            } else {
                mapSection.style.display = 'none';
            }
        }
    </script>
</body>
</html>