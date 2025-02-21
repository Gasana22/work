<?php
include 'includes/db.php';

session_start();
if (isset($_SESSION['user_id']) && isset($_POST['post_id'])) {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];
    $comment = $_POST['comment'];

    $query = "INSERT INTO comments (post_id, user_id, comment) VALUES ('$post_id', '$user_id', '$comment')";
    if (mysqli_query($conn, $query)) {
        echo "Comment added!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<form method="POST">
    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
    <textarea name="comment" placeholder="Add a comment"></textarea>
    <button type="submit">Comment</button>
</form>
