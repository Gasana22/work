<?php
include 'includes/db.php';
include 'includes/functions.php';
session_start();

if (isset($_SESSION['user_id']) && isset($_POST['notification_id'])) {
    $notification_id = $_POST['notification_id'];

    $query = "UPDATE notifications SET is_read=TRUE WHERE id='$notification_id'";
    if (mysqli_query($conn, $query)) {
        echo "Notification marked as read!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Please log in.";
}
?>
