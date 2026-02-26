<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>E-Waste Connect | Sustainable Recycling</title>
<link rel="manifest" href="manifest.json">

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap');

:root {
    --green: #2ecc71;
    --dark: #2c3e50;
}

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    margin: 0;
    background: #f8fbf9;
    color: var(--dark);
    scroll-behavior: smooth;
}

/* Navigation */
nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 8%;
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(10px);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.logo a {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
}

.logo img {
    height: 45px;
}

.logo span {
    font-weight: 800;
    font-size: 1.5rem;
    color: var(--dark);
}

.nav-links a {
    text-decoration: none;
    color: var(--dark);
    margin-left: 30px;
    font-weight: 600;
    transition: 0.3s;
}

.nav-links a:hover {
    color: var(--green);
}

.login-btn {
    background: var(--green);
    color: white !important;
    padding: 10px 25px;
    border-radius: 50px;
}

/* Hero Section */
.hero-home {
    background: linear-gradient(rgba(10,30,20,0.75), rgba(0,0,0,0.85)),
    url('https://images.unsplash.com/photo-1550009158-9ebf69173e03?auto=format&fit=crop&w=1600&q=80');
    background-size: cover;
    background-position: center;
    height: 85vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    border-radius: 0 0 80px 80px;
}

.hero-home h1 {
    font-size: 4rem;
    margin: 0;
    font-weight: 800;
}

.hero-home p {
    font-size: 1.2rem;
    max-width: 700px;
    margin: 25px 0;
}

.btn-main {
    padding: 16px 40px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 700;
    transition: 0.3s;
    display: inline-block;
}

.btn-green {
    background: var(--green);
    color: white;
}

.btn-outline {
    border: 2px solid white;
    color: white;
    margin-left: 15px;
}

.btn-green:hover {
    background: #27ae60;
    transform: translateY(-3px);
}

/* Sections */
.section-container {
    max-width: 1200px;
    margin: -100px auto 80px;
    padding: 0 20px;
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 30px;
}

.feature-card {
    background: white;
    padding: 45px;
    border-radius: 35px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.05);
    text-align: center;
    transition: 0.4s;
}
.feature-card {
    text-decoration: none;
    color: var(--dark);
}


.feature-card:hover {
    transform: translateY(-12px);
    box-shadow: 0 40px 70px rgba(46, 204, 113, 0.15);
}

.icon-circle {
    width: 90px;
    height: 90px;
    background: #eafaf1;
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    font-size: 2.5rem;
}

/* About */
#about {
    padding: 80px 10%;
    text-align: center;
    background: white;
    margin-top: 50px;
    border-radius: 50px;
}

#about h2 {
    font-size: 2.8rem;
    color: #1e8449;
}

/* Contact */
#contact {
    padding: 100px 8%;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
}

.contact-info h2 {
    font-size: 3rem;
}

.contact-info span {
    color: var(--green);
}

.contact-form {
    background: white;
    padding: 40px;
    border-radius: 30px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.05);
}

.contact-form input,
.contact-form textarea {
    width: 100%;
    padding: 15px;
    margin-bottom: 20px;
    border: 2px solid #f0f0f0;
    border-radius: 12px;
    font-family: inherit;
}

.contact-form button {
    width: 100%;
    background: var(--dark);
    color: white;
    border: none;
    padding: 18px;
    border-radius: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.3s;
}

.contact-form button:hover {
    background: var(--green);
}

/* Footer */
footer {
    background: #1a252f;
    color: white;
    padding: 60px 8%;
    text-align: center;
}

footer p:first-child {
    font-size: 1.2rem;
    font-weight: 700;
}

footer p:last-child {
    opacity: 0.6;
}

@media (max-width: 768px) {
    #contact {
        grid-template-columns: 1fr;
    }
    .hero-home h1 {
        font-size: 2.5rem;
    }
}
</style>
</head>

<body>

<nav>
    <div class="logo">
        <a href="index.php">
            <img src="logo.png" alt="Logo">
            <span>E-Waste</span>
        </a>
    </div>

    <div class="nav-links">
        <a href="#about">About</a>
        <a href="#services">Services</a>
        <a href="#contact">Contact</a>
        <a href="login.php" class="login-btn">Login</a>
    </div>
</nav>

<header class="hero-home">
    <h1>Turn Trash into Treasure</h1>
    <p>A smart marketplace for responsible electronic waste disposal.</p>
    <div>
        <a href="login.php" class="btn-main btn-green">Post Your Waste</a>
        <a href="register.php" class="btn-main btn-outline">Join as a Shop</a>
    </div>
</header>

<div class="section-container" id="services">
    <div class="grid">
        <a href="login.php" class="feature-card">
            <div class="icon-circle">👤</div>
            <h2>Individual Users</h2>
            <p>Upload your e-waste and get competitive bids from shops.</p>
        </a>

        <a href="login.php" class="feature-card">
            <div class="icon-circle">🏬</div>
            <h2>Certified Shops</h2>
            <p>Access live e-waste posts and grow your recycling business.</p>
        </a>

         <a href="login.php" class="feature-card">
            <div class="icon-circle">👨‍💼</div>
            <h2>Admin Panel</h2>
            <p>
                Manage certified shops and users, verify e-waste posts,
                monitor transactions, and ensure smooth platform operations.
            </p>
        </a>
    </div>
</div>

<section id="about">
    <h2>How It Works</h2>
    <div class="grid" style="margin-top:40px;">
        <div><h3>1. Snap & Post</h3></div>
        <div><h3>2. Get Offers</h3></div>
        <div><h3>3. Secure Deal</h3></div>
    </div>
</section>

<section id="contact">
    <div class="contact-info">
        <h2>Get in <span>Touch</span></h2>
        <p>📍 Mangaluru, Karnataka, India</p>
        <p>📧 support@ewasteconnect.com</p>
        <p>📞 +91 98765 43210</p>
    </div>

    <div class="contact-form">
        <form>
            <input type="text" placeholder="Your Name" required>
            <input type="email" placeholder="Your Email" required>
            <textarea rows="5" placeholder="How can we help?"></textarea>
            <button type="submit">Send Message</button>
        </form>
    </div>
</section>

<footer>
    <p>♻️ E-Waste Connect</p>
    <p>&copy; 2026 E-Waste Connect Project. All rights reserved.</p>
</footer>
<script>
    // Service Worker
        if ("serviceWorker" in navigator) {
            navigator.serviceWorker.register("service-worker.js")
                .then(() => console.log("Service Worker Registered"))
                .catch(err => console.error("SW error", err));
        }
</script>

</body>
</html>
