<nav class="navbar">
    <div class="container" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <a href="dashboard.php" class="navbar-brand" style="text-decoration: none; display: flex; align-items: center; gap: 12px;">
            <img src="logo.png" alt="E-Waste Logo" class="brand-logo" style="height: 40px; width: auto;">
            <span class="brand-text" style="font-weight: 800; font-size: 1.3rem; color: #2c3e50; font-family: 'Plus Jakarta Sans', sans-serif;">E-Waste <span style="color: #2ecc71;">Connect</span></span>
        </a>

        <div class="nav-user-info" style="display: flex; align-items: center; gap: 20px;">
            <?php if(isset($_SESSION['username'])): ?>
                <span style="color: #7f8c8d; font-size: 0.9rem;">
                    <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                </span>
                <a href="profile.php" class="btn" style="padding: 10px 20px; font-size: 0.8rem; background: #f0f2f0; text-decoration: none; border-radius: 8px; color: #333;">Profile</a>
                <a href="logout.php" class="btn-logout" style="color: #ff4d4d; text-decoration: none; font-weight: 600;">Logout</a>
            <?php endif; ?>
        </div>
    </div>
</nav>