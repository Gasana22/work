<?php
require 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$conversation_user_id = $_POST['user_id'] ?? '';
$is_typing = $_POST['is_typing'] ?? false;

$stmt = $dbh->prepare("UPDATE conversations SET is_typing = ? WHERE (user_id = ? AND conversation_user_id = ?) OR (user_id = ? AND conversation_user_id = ?)");
$stmt->execute([$is_typing, $user_id, $conversation_user_id, $conversation_user_id, $user_id]);
?>
