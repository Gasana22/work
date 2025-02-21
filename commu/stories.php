<?php
require 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch stories from the users the logged-in user follows
$stmt = $dbh->prepare("SELECT s.id, s.content, s.media, s.created_at, u.username, u.avatar FROM stories s JOIN users u ON s.user_id = u.id JOIN follows f ON f.following_id = s.user_id WHERE f.follower_id = ? AND s.created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY) ORDER BY s.created_at DESC");
$stmt->execute([$user_id]);
$stories = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Stories</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #001f3f;
        }
        .story {
            display: flex;
            align-items: center;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        img {
            max-width: 100%;
            border-radius: 50%;
        }
        .avatar {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }
        .content {
            flex: 1;
        }
        small {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Stories</h1>
        <?php foreach ($stories as $story): ?>
            <div class="story">
                <img src="<?php echo htmlspecialchars($story['avatar']); ?>" class="avatar" alt="Avatar">
                <div class="content">
                    <strong><?php echo htmlspecialchars($story['username']); ?></strong>
                    <p><?php echo nl2br(htmlspecialchars($story['content'])); ?></p>
                    <?php if ($story['media']): ?>
                        <img src="<?php echo htmlspecialchars($story['media']); ?>" alt="Media">
                    <?php endif; ?>
                    <small><?php echo htmlspecialchars($story['created_at']); ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
