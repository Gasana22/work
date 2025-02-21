<?php
require 'config.php';
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$message_id = $_POST['message_id'] ?? '';

// Prepare and execute the delete statement
$stmt = $dbh->prepare("DELETE FROM messages WHERE id = ?");
if ($stmt->execute([$message_id])) {
    echo "Message deleted successfully!";
} else {
    echo "Failed to delete message.";
}
?>
