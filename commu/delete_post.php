<?php
require 'config.php';
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$post_id = $_POST['post_id'] ?? '';

// Prepare and execute the delete statement
$stmt = $dbh->prepare("DELETE FROM posts WHERE id = ?");
if ($stmt->execute([$post_id])) {
    echo "Post deleted successfully!";
} else {
    echo "Failed to delete post.";
}
?>
