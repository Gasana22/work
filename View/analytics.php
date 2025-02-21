<?php
include 'includes/db.php';

session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $query = "
        SELECT 
            (SELECT COUNT(*) FROM posts WHERE user_id='$user_id') AS total_posts,
            (SELECT COUNT(*) FROM followers WHERE following_id='$user_id') AS total_followers,
            (SELECT COUNT(*) FROM likes WHERE post_id IN (SELECT id FROM posts WHERE user_id='$user_id')) AS total_likes
    ";
    $result = mysqli_query($conn, $query);
    $analytics = mysqli_fetch_assoc($result);

    echo "Total Posts: " . $analytics['total_posts'] . "<br>";
    echo "Total Followers: " . $analytics['total_followers'] . "<br>";
    echo "Total Likes: " . $analytics['total_likes'] . "<br>";
} else {
    echo "Please log in.";
}
?>
