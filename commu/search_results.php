<?php
require 'config.php';
session_start();

if (!isset($_GET['query'])) {
    echo "No search query provided.";
    exit;
}

$query = $_GET['query'] ?? '';

// Determine if the query is a hashtag or a regular search term
$is_hashtag = strpos($query, '#') === 0;
$results = [];

if ($is_hashtag) {
    // Remove the '#' character
    $hashtag = ltrim($query, '#');

    // Prepare and execute the select statement for hashtags
    $stmt = $dbh->prepare("SELECT p.id, p.content, p.media, p.created_at, u.username, u.avatar FROM posts p JOIN users u ON p.user_id = u.id JOIN post_hashtags ph ON p.id = ph.post_id WHERE ph.hashtag = ? ORDER BY p.created_at DESC");
    $stmt->execute([$hashtag]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Prepare and execute the select statement for regular search terms
    $stmt = $dbh->prepare("SELECT * FROM ugc WHERE title LIKE ? OR content LIKE ?");
    $stmt->execute(["%$query%", "%$query%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
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
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
        }
        img {
            max-width: 100%;
            border-radius: 50%;
        }
        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        a {
            color: #001f3f;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Search Results</h1>
        <ul>
            <?php if ($is_hashtag): ?>
                <?php foreach ($results as $result): ?>
                    <li>
                        <div>
                            <img src="<?php echo htmlspecialchars($result['avatar']); ?>" class="avatar" alt="Avatar">
                            <strong><?php echo htmlspecialchars($result['username']); ?></strong>
                            <small><?php echo htmlspecialchars($result['created_at']); ?></small>
                        </div>
                        <p><?php echo nl2br(htmlspecialchars($result['content'])); ?></p>
                        <?php if ($result['media']): ?>
                            <img src="<?php echo htmlspecialchars($result['media']); ?>" alt="Media">
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach ($results as $result): ?>
                    <li><a href="view_content.php?id=<?php echo htmlspecialchars($result['id']); ?>"><?php echo htmlspecialchars($result['title']); ?></a></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
