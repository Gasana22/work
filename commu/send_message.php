<?php
require 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'] ?? '';
$message = $_POST['message'] ?? '';

// Prepare and execute the insert statement
$stmt = $dbh->prepare("INSERT INTO messages (sender_id, receiver_id, message, read_status) VALUES (?, ?, ?, 0)");
if ($stmt->execute([$sender_id, $receiver_id, $message])) {
    // Update typing status
    $stmt = $dbh->prepare("UPDATE users SET typing_status = 0 WHERE id = ?");
    $stmt->execute([$sender_id]);
    
    header("Location: messages.php?user_id=$receiver_id");
    exit;
} else {
    echo "Failed to send message.";
}
?>
