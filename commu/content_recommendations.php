<?php
require 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch personalized content recommendations
$recommendations_stmt = $dbh->prepare("SELECT p.id, p.content, p.media, p.created_at, u.username, u.avatar FROM posts p JOIN users u ON p.user_id = u.id WHERE p.user_id IN (SELECT following_id FROM follows WHERE follower_id = ?) ORDER BY p.created_at DESC LIMIT 10");
$recommendations_stmt->execute([$user_id]);
$recommendations = $recommendations_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Content Recommendations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #001f3f;
        }
        .post {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
            margin-bottom: 10px;
        }
        .post img {
            max-width: 100%;
            border-radius: 8px;
        }
        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        .content {
            margin: 10px 0;
        }
        .actions {
            display: flex;
            justify-content: space-between;
        }
        button {
            background-color: #001f3f;
            color: #fff;
            cursor: pointer;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
        }
        button:hover {
            background-color: #003366;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Content Recommendations</h1>
        <?php foreach ($recommendations as $post): ?>
            <div class="post">
                <div class="user-info">
                    <img src="<?php echo htmlspecialchars($post['avatar']); ?>" class="avatar" alt="Avatar">
                    <strong><?php echo htmlspecialchars($post['username']); ?></strong>
                    <small><?php echo htmlspecialchars($post['created_at']); ?></small>
                </div>
                <div class="content">
                    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                    <?php if ($post['media']): ?>
                        <img src="<?php echo htmlspecialchars($post['media']); ?>" alt="Media">
                    <?php endif; ?>
                </div>
                <div class="actions">
                    <button>Like</button>
                    <button>Comment</button>
                    <button>Share</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
