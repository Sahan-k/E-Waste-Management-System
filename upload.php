<?php
include 'db.php'; // Connection file
session_start();

if ($_FILES['waste_image']) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["waste_image"]["name"]);
    move_uploaded_file($_FILES["waste_image"]["tmp_name"], $target_file);

    $desc = $_POST['description'];
    $uid = $_SESSION['user_id'];

   // $sql = "INSERT INTO waste_posts (user_id, image_path, description) VALUES ('$uid', '$target_file', '$desc')";
   $sql = "INSERT INTO waste_posts (user_id, image_path, description, status) 
        VALUES ('$uid', '$target_file', '$desc', 'pending')";
    if ($conn->query($sql)) {
        echo json_encode(['success' => true]);
    }
}
