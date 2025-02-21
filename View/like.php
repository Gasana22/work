<?php
include 'includes/db.php';

session_start();
if (isset($_SESSION['user_id']) && isset($_GET['post_id'])) {
    $user_id = $_SESSION['user_id'];
    $post_id = $_GET['post_id'];

    $query = "INSERT INTO likes (post_id, user_id) VALUES ('$post_id', '$user_id')";
    if (mysqli_query($conn, $query)) {
        echo "Post liked!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Please log in.";
}
?>
