<?php 
include 'db.php';
session_start();

// Security: Only allow Admin
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch Statistics
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_posts = $conn->query("SELECT COUNT(*) as count FROM waste_posts")->fetch_assoc()['count'];
$total_deals = $conn->query("SELECT COUNT(*) as count FROM waste_posts WHERE status='accepted'")->fetch_assoc()['count'];

// Fetch All Users
$users_result = $conn->query("SELECT * FROM users");

// Fetch All Waste Posts
$posts_query = "SELECT w.*, u.username as owner, s.username as shop_name 
                FROM waste_posts w 
                LEFT JOIN users u ON w.user_id = u.id 
                LEFT JOIN users s ON w.shop_id = s.id";
$posts_result = $conn->query($posts_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | E-Waste System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; margin: 0; }
        .header { background: #2c3e50; padding: 20px 5%; display: flex; justify-content: space-between; align-items: center; color: white; }
        .container { max-width: 1200px; margin: 20px auto; padding: 20px; }
        
        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center; border-bottom: 5px solid #27ae60; }
        .stat-card h3 { margin: 0; color: #7f8c8d; font-size: 0.9rem; text-transform: uppercase; }
        .stat-card p { margin: 10px 0 0; font-size: 2rem; font-weight: bold; color: #2c3e50; }

        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 40px;}
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #34495e; color: white; font-size: 0.85rem; }
        
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; }
        .status-accepted { background: #e9f7ef; color: #27ae60; }
        .status-offered { background: #ebf5fb; color: #2980b9; }
        .status-pending { background: #fef5e7; color: #d35400; }

        .btn-delete {
            background: #ff7675;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.8rem;
            transition: 0.3s;
        }
        .btn-delete:hover { background: #d63031; }
    </style>
</head>
<body>

  <div class="header" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 5%;">
    <div style="display: flex; align-items: center; gap: 15px;">
        <img src="logo.png" alt="Logo" style="height: 50px; width: 50;">
        <h1 style="margin: 0; font-size: 1.5rem;">Admin Control Center</h1>
    </div>
    <a href="logout.php" style="color: #ff7675; text-decoration: none; font-weight: bold;">Logout</a>
</div>

    <div class="container">
        
        <?php if(isset($_GET['msg'])): ?>
            <div style="background: #d1edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                ✅ <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Users</h3>
                <p><?php echo $total_users; ?></p>
            </div>
            <div class="stat-card" style="border-bottom-color: #3498db;">
                <h3>Active Posts</h3>
                <p><?php echo $total_posts; ?></p>
            </div>
            <div class="stat-card" style="border-bottom-color: #f1c40f;">
                <h3>Successful Deals</h3>
                <p><?php echo $total_deals; ?></p>
            </div>
        </div>

        <h3>1. User Management</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Location</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = $users_result->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $user['id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                    <td><?php echo strtoupper($user['role']); ?></td>
                    <td><?php echo $user['lat'] ? "📍 Set" : "Not Set"; ?></td>
                    <td>
                        <?php if($user['role'] != 'admin'): ?>
                            <a href="delete_action.php?type=user&id=<?php echo $user['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure? This will permanently delete this user account.')">Remove</a>
                        <?php else: ?>
                            <small style="color:gray;">Protected</small>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h3>2. Activity Log</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item</th>
                    <th>Description</th>
                    <th>User</th>
                    <th>Offer By</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($post = $posts_result->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $post['id']; ?></td>
                    <td><img src="uploads/<?php echo basename($post['image_path']); ?>" width="50" style="border-radius:4px;" onerror="this.src='https://via.placeholder.com/50'"></td>
                    <td><?php echo htmlspecialchars($post['description']); ?></td>
                    <td><?php echo htmlspecialchars($post['owner']); ?></td>
                    <td><?php echo $post['shop_name'] ?? "---"; ?></td>
                    <td>₹<?php echo number_format($post['price_offer'], 2); ?></td>
                    <td>
                        <span class="status-badge status-<?php echo $post['status']; ?>">
                            <?php 
                                if($post['status'] == 'accepted') echo "✅ DEAL CLOSED";
                                else if($post['status'] == 'offered') echo "💰 OFFER SENT";
                                else echo "🕒 PENDING";
                            ?>
                        </span>
                    </td>
                    <td>
                        <a href="delete_action.php?type=post&id=<?php echo $post['id']; ?>" class="btn-delete" onclick="return confirm('Delete this post? This cannot be undone.')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>