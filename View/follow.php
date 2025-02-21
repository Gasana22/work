<?php
include 'includes/db.php';

session_start();
if (isset($_SESSION['user_id']) && isset($_GET['user_id'])) {
    $follower_id = $_SESSION['user_id'];
    $following_id = $_GET['user_id'];

    $query = "INSERT INTO followers (follower_id, following_id) VALUES ('$follower_id', '$following_id')";
    if (mysqli_query($conn, $query)) {
        echo "Followed successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Please log in.";
}
