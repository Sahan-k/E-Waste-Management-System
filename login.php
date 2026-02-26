<?php
include 'db.php';
session_start();

$error_msg = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, role FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];

        if ($user['role'] == 'admin') header("Location: admin.php");
        elseif ($user['role'] == 'shop') header("Location: shop_panel.php");
        else header("Location: dashboard.php");
        exit();
    } else {
        $error_msg = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | E-Waste Connect</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap');

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow: hidden;
        }

        /* Technical E-Waste Background */
        .bg-container {
           font-family: 'Plus Jakarta Sans', sans-serif;
     background: 
        
        url('yashu.png');
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


        /* Glassmorphism Effect */
        .login-card {
            position: relative;
    width: 100%;
    max-width: 420px;
    padding: 45px 35px;
    border-radius: 25px;
    color: white;
    text-align: center;
    background: rgba(15, 20, 30, 0.5);
    backdrop-filter: blur(5px);
            animation: slideUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-area h1 {
            font-size: 2.4rem;
            margin: 0;
            font-weight: 800;
            letter-spacing: -1.5px;
        }

        .logo-area span {
            color: #2ecc71;
            text-shadow: 0 0 15px rgba(46, 204, 113, 0.4);
        }

        .tagline {
            font-size: 0.9rem;
            opacity: 0.7;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 40px;
            display: block;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 16px 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            color: white;
            font-size: 1rem;
            box-sizing: border-box;
            outline: none;
            transition: all 0.3s;
        }

        input::placeholder { color: rgba(255,255,255,0.4); }

        input:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #2ecc71;
            box-shadow: 0 0 20px rgba(46, 204, 113, 0.2);
        }

        button {
            width: 100%;
            padding: 16px;
            background: #2ecc71;
            color: #051a0e; 
            border: none;
            border-radius: 16px;
            font-size: 1.1rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.4s ease;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        button:hover {
            background: #27ae60;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(46, 204, 113, 0.4);
        }

        .error-msg {
            background: rgba(255, 118, 117, 0.2);
            border: 1px solid #ff7675;
            color: #ff7675;
            padding: 10px;
            border-radius: 12px;
            font-size: 0.85rem;
            margin-bottom: 20px;
        }

        .footer-links {
            margin-top: 35px;
            font-size: 0.95rem;
            color: rgba(255,255,255,0.6);
        }

        .footer-links a {
            color: #2ecc71;
            text-decoration: none;
            font-weight: 700;
            transition: 0.3s;
        }

        .footer-links a:hover {
            text-decoration: underline;
            color: #27ae60;
        }
    </style>
</head>
<body>

    <div class="bg-container">
        <div class="login-card">
            <div class="logo-area" style="text-align: center;">
    <img src="logo.png" alt="Logo" style="height: 80px; width: auto; margin-bottom: 10px; border-radius: 8px;">
    <h1 style="margin-top: 0;">E-Waste <span>Connect</span></h1>
    <span class="tagline">Sustainable Disposal Solutions</span>
</div>
            <?php if($error_msg): ?>
                <div class="error-msg"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" required autocomplete="off">
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                
                <button type="submit" name="login">Access Dashboard</button>
            </form>

            <div class="footer-links">
                Don't have an account? <a href="register.php">Sign up here</a>
            </div>
        </div>
    </div>

</body>
</html>