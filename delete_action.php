<?php
include 'db.php';
 include 'header.php';
session_start();

// 1. Security Check: Only allow logged-in Admins
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit("Unauthorized access.");
}

// 2. Validate the incoming request
if (isset($_GET['type']) && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $type = $_GET['type'];
    $message = "";

    if ($type == 'user') {
        // Prevent deleting the currently logged-in admin
        if ($id == $_SESSION['user_id']) {
            header("Location: admin.php?msg=Error: You cannot delete your own account.");
            exit();
        }

        // Delete User Logic
        $sql = "DELETE FROM users WHERE id = '$id' AND role != 'admin'";
        if ($conn->query($sql)) {
            $message = "User account removed successfully.";
        } else {
            $message = "Error removing user.";
        }

    } elseif ($type == 'post') {
        // Delete Waste Post Logic
        // First: Optional - find image path to delete file from folder
        $res = $conn->query("SELECT image_path FROM waste_posts WHERE id = '$id'");
        $post_data = $res->fetch_assoc();
        
        $sql = "DELETE FROM waste_posts WHERE id = '$id'";
        if ($conn->query($sql)) {
            // Optional: delete the physical file from the uploads folder
            if ($post_data && file_exists($post_data['image_path'])) {
                unlink($post_data['image_path']);
            }
            $message = "Waste post deleted successfully.";
        } else {
            $message = "Error deleting post.";
        }
    }

    // 3. Redirect back with the result message
    header("Location: admin.php?msg=" . urlencode($message));
    exit();

} else {
    // If someone tries to access this file directly without parameters
    header("Location: admin.php");
    exit();
}
?>