<?php
require 'config.php';
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$user_id = $_POST['user_id'] ?? '';

// Prepare and execute the delete statement
$stmt = $dbh->prepare("DELETE FROM users WHERE id = ?");
if ($stmt->execute([$user_id])) {
    echo "User deleted successfully!";
} else {
    echo "Failed to delete user.";
}
?>
