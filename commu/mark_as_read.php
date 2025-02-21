<?php
require 'config.php';
session_start();

// Check if the message ID is provided
if (!isset($_POST['message_id'])) {
    echo "Invalid request.";
    exit;
}

$message_id = $_POST['message_id'];

// Update read status
$stmt = $dbh->prepare("UPDATE messages SET read_status = 1 WHERE id = ?");
$stmt->execute([$message_id]);
?>
