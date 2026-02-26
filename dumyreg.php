<?php
include 'db.php';
$msg = "";

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = 'user'; // Default role for new sign-ups

    // Check if username already exists
    $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
    if ($check->num_rows > 0) {
        $msg = "Username already taken!";
    } else {
        // Insert new user
        $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
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
    <link rel="stylesheet" href="style.css"> <style>
        /* Reusing some logic from your login.php for a matching look */
        body { font-family: 'Inter', sans-serif; background: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .reg-card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 350px; text-align: center; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #2ecc71; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; }
        .msg { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="reg-card">
        <h2>Create Account</h2>
        <?php if($msg) echo "<p class='msg'>$msg</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Choose Username" required>
            <input type="password" name="password" placeholder="Create Password" required>
            <button type="submit" name="register">Register Now</button>
        </form>
        <p style="margin-top:20px;">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>