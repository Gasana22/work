<?php
include 'includes/db.php';
include 'includes/functions.php';
session_start();

if (isset($_SESSION['user_id']) && isset($_GET['user']) && isset($_GET['post_id'])) {
    $username = $_GET['user'];
    $post_id = $_GET['post_id'];

    $user_query = "SELECT id FROM users WHERE username='$username'";
    $user_result = mysqli_query($conn, $user_query);
    if ($user_data = mysqli_fetch_assoc($user_result)) {
        $mentioned_user_id = $user_data['id'];
        add_notification($mentioned_user_id, "You were mentioned in a post by User ID: " . $_SESSION['user_id']);
        echo "Notification added!";
    } else {
        echo "User not found!";
    }
} else {
    echo "Invalid request!";
}
?>
