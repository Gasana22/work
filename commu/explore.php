<?php
require 'config.php';
session_start();

// Fetch trending posts
$stmt = $dbh->query("SELECT p.id, p.content, p.media, p.created_at, u.username, u.avatar FROM posts p JOIN users u ON p.user_id = u.id ORDER BY p.likes_count DESC, p.comments_count DESC LIMIT 10");
$trending_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch popular hashtags
$hashtags_stmt = $dbh->query("SELECT hashtag, COUNT(*) AS count FROM post_hashtags GROUP BY hashtag ORDER BY count DESC LIMIT 10");
$popular_hashtags = $hashtags_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user recommendations
$user_recommendations_stmt = $dbh->query("SELECT id, username, avatar FROM users ORDER BY followers_count DESC LIMIT 10");
$user_recommendations = $user_recommendations_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Explore</title>
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
        .section {
            margin: 20px 0;
        }
        .post, .hashtag, .user {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }
        .post img, .user img {
            max-width: 100%;
            border-radius: 50%;
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
        <h1>Explore</h1>
        <div class="section">
            <h2>Trending Posts</h2>
            <?php foreach ($trending_posts as $post): ?>
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
        <div class="section">
            <h2>Popular Hashtags</h2>
            <?php foreach ($popular_hashtags as $hashtag): ?>
                <div class="hashtag">
                    <a href="search_results.php?query=%23<?php echo htmlspecialchars($hashtag['hashtag']); ?>">#<?php echo htmlspecialchars($hashtag['hashtag']); ?></a> (<?php echo htmlspecialchars($hashtag['count']); ?> posts)
                </div>
            <?php endforeach; ?>
        </div>
        <div class="section">
            <h2>User Recommendations</h2>
            <?php foreach ($user_recommendations as $user): ?>
                <div class="user">
                    <img src="<?php echo htmlspecialchars($user['avatar']); ?>" class="avatar" alt="Avatar">
                    <strong><a href="view_profile.php?user_id=<?php echo htmlspecialchars($user['id']); ?>"><?php echo htmlspecialchars($user['username']); ?></a></strong>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
