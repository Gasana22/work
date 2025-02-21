<?php
include 'includes/db.php';
include 'includes/functions.php';
session_start();

function linkify($text) {
    // Linkify hashtags
    $text = preg_replace('/#(\w+)/', '<a href="hashtag.php?tag=$1">#$1</a>', $text);
    // Linkify mentions
    $text = preg_replace('/@(\w+)/', '<a href="profile.php?user=$1">@$1</a>', $text);
    return $text;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogging Platform</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Welcome to Our Blogging Platform</h1>
    </header>
    <nav>
        <a href="index.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
        <a href="messages.php">Messages</a>
        <a href="notifications.php">Notifications</a>
    </nav>
    <div class="container">
        <?php
        $query = "SELECT * FROM posts ORDER BY created_at DESC";
        $result = mysqli_query($conn, $query);

        while ($post = mysqli_fetch_assoc($result)) {
            $post_id = $post['id'];
            // Fetch like count
            $like_query = "SELECT COUNT(*) as like_count FROM likes WHERE post_id='$post_id'";
            $like_result = mysqli_query($conn, $like_query);
            $like_data = mysqli_fetch_assoc($like_result);
            $like_count = $like_data['like_count'];

            echo "<div class='card'>";
            echo "<h2>" . $post['title'] . "</h2>";
            echo "<p>" . linkify($post['content']) . "</p>";

            if ($post['media']) {
                $media_path = "uploads/" . $post['media'];
                // Display media based on type
                if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $post['media'])) {
                    echo "<img src='$media_path' alt='Media' style='max-width: 100%; height: auto;'>";
                } elseif (preg_match('/\.(mp4|webm|ogg)$/i', $post['media'])) {
                    echo "<video controls style='max-width: 100%;'>
                            <source src='$media_path' type='video/mp4'>
                            Your browser does not support the video tag.
                          </video>";
                }
            }

            echo "<small>Posted by User ID: " . $post['user_id'] . " on " . $post['created_at'] . "</small>";
            echo "<button class='btn like-btn' data-post-id='" . $post['id'] . "'>Like</button>";
            echo "<span class='like-count'>" . $like_count . "</span>";

            // Fetch comments for the post
            $comment_query = "SELECT * FROM comments WHERE post_id='$post_id' ORDER BY created_at DESC";
            $comment_result = mysqli_query($conn, $comment_query);

            echo "<div class='comments'>";
            while ($comment = mysqli_fetch_assoc($comment_result)) {
                echo "<div class='comment'>";
                echo "<p>" . linkify($comment['comment']) . "</p>";
                echo "<small>Commented by User ID: " . $comment['user_id'] . " on " . $comment['created_at'] . "</small>";
                echo "</div>";
            }
            echo "</div>";

            // Comment form
            if (isset($_SESSION['user_id'])) {
                echo "<form method='POST' action='comment.php'>
                        <input type='hidden' name='post_id' value='$post_id'>
                        <textarea name='comment' placeholder='Add a comment'></textarea>
                        <button type='submit' class='btn'>Comment</button>";
                // Handle mentions in comments
                echo "<script>
                document.querySelectorAll('textarea[name=comment]').forEach(textarea => {
                    textarea.addEventListener('blur', () => {
                        const mentions = textarea.value.match(/@(\w+)/g);
                        if (mentions) {
                            mentions.forEach(mention => {
                                const username = mention.slice(1);
                                fetch(`mention_user.php?user=${username}&post_id=${post_id}`)
                                    .then(response => response.text())
                                    .then(data => {
                                        console.log(data);
                                    });
                            });
                        }
                    });
                });
                </script>";
                echo "</form>";
            } else {
                echo "<p>Please log in to comment.</p>";
            }

            echo "</div>";
        }
        ?>
    </div>
    <footer>
        <p>&copy; 2025 Blogging Platform. All rights reserved.</p>
    </footer>

    <!-- JavaScript for Like Button -->
    <script>
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', () => {
            const postId = button.getAttribute('data-post-id');
            fetch('like.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `post_id=${postId}`
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    const likeCount = button.nextElementSibling;
                    likeCount.textContent = parseInt(likeCount.textContent) + 1;
                    // Get post owner and add notification
                    fetch(`get_post_owner.php?post_id=${postId}`)
                        .then(response => response.json())
                        .then(postOwner => {
                            fetch(`add_notification.php?user_id=${postOwner}&message=Your post was liked by User ID: ${<?php echo $_SESSION['user_id']; ?>}`)
                                .then(response => response.text())
                                .then(data => {
                                    console.log(data);
                                });
                        });
                }
            });
        });
    });
    </script>
</body>
</html>
