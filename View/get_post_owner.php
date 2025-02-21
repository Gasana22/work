<?php
include 'includes/db.php';
session_start();

if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    $post_query = "SELECT user_id FROM posts WHERE id='$post_id'";
    $post_result = mysqli_query($conn, $post_query);
    $post_owner = mysqli_fetch_assoc($post_result)['user_id'];
