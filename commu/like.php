<?php
require 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$content_id = $_POST['content_id'] ?? null;
content_type = $_POST['content_type'] ?? null;

if ($content_id && $content_type) {
    // Prepare and execute the insert statement
    $stmt = $dbh->prepare("INSERT INTO likes (user_id, content_id, content_type) VALUES (?, ?, ?)");
    if ($stmt->execute([$user_id, $content_id, $content_type])) {
        echo "Content liked successfully!";
    } else {
        echo "Failed to like content.";
    }
} else {
    echo "Content ID and Content Type are required.";
}
?>
