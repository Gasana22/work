<?php
require 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message_id = $_POST['message_id'] ?? '';

$stmt = $dbh->prepare("UPDATE messages SET is_read = 1 WHERE id = ? AND receiver_id = ?");
$stmt->execute([$message_id, $user_id]);
?>
