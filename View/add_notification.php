<?php
include 'includes/db.php';
include 'includes/functions.php';
session_start();

if (isset($_GET['user_id']) && isset($_GET['message'])) {
    $user_id = $_GET['user_id'];
    $message = $_GET['message'];

    add_notification($user_id, $message);
    echo "Notification added!";
} else {
    echo "Invalid request!";
}
?>
