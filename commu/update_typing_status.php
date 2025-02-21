<?php
require 'config.php';
session_start();

// Check if the user ID and typing status are provided
if (!isset($_POST['user_id']) || !isset($_POST['status'])) {
    echo "Invalid request.";
    exit;
}

$user_id = $_POST['user_id'];
$typing_status = (int)$_POST['status'];

// Update typing status
$stmt = $dbh->prepare("UPDATE users SET typing_status = ? WHERE id = ?");
$stmt->execute([$typing_status, $user_id]);
?>
