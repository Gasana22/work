<?php
require 'config.php';
session_start();

// Check if the user ID is provided
if (!isset($_GET['user_id'])) {
    echo "No user ID provided.";
    exit;
}

$user_id = $_GET['user_id'] ?? '';

// Fetch user information
$stmt = $dbh->prepare("SELECT username, bio, avatar, followers_count, following_count FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch user posts
$stmt = $dbh->prepare("SELECT id, content, media, created_at FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($user['username']); ?>'s Profile</title>
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
        .profile-header {
            display: flex;
            align-items: center;
        }
        .profile-header img {
            max-width: 100%;
            border-radius: 50%;
        }
        .avatar {
            width: 100px;
            height: 100px;
            margin-right: 20px;
        }
        .bio {
            margin: 10px 0;
        }
        .stats {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
        }
        .posts {
            margin-top: 20px;
        }
        .post {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }
        .post img {
            max-width: 100%;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-header">
            <img src="<?php echo htmlspecialchars($user['avatar']); ?>" class="avatar" alt="Avatar">
            <div>
                <h1><?php echo htmlspecialchars($user['username']); ?></h1>
                <p class="bio"><?php echo nl2br(htmlspecialchars($user['bio'])); ?></p>
                <div class="stats">
                    <span>Followers: <?php echo htmlspecialchars($user['followers_count']); ?></span>
                    <span>Following: <?php echo htmlspecialchars($user['following_count']); ?></span>
                </div>
            </div>
        </div>
        <div class="posts">
            <h2>Posts</h2>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                    <?php if ($post['media']): ?>
                        <img src="<?php echo htmlspecialchars($post['media']); ?>" alt="Media">
                    <?php endif; ?>
                    <small><?php echo htmlspecialchars($post['created_at']); ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
