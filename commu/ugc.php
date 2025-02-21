<?php
require 'config.php';

// Prepare and execute the select statement
$stmt = $dbh->query("SELECT u.username, c.title, c.content, c.media, c.created_at, c.id AS content_id FROM ugc c JOIN users u ON c.user_id = u.id ORDER BY c.created_at DESC");
$contents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User-Generated Content</title>
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
        h2 {
            color: #003366;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
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
        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        img {
            max-width: 100%;
        }
        small {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User-Generated Content</h1>
        <ul>
            <?php foreach ($contents as $content): ?>
                <li>
                    <h2><?php echo htmlspecialchars($content['title']); ?></h2>
                    <p>by <?php echo htmlspecialchars($content['username']); ?> on <?php echo htmlspecialchars($content['created_at']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($content['content'])); ?></p>
                    <?php if ($content['media']): ?>
                        <img src="<?php echo htmlspecialchars($content['media']); ?>" alt="Media">
                    <?php endif; ?>
                    <form method="POST" action="like.php">
                        <input type="hidden" name="content_id" value="<?php echo htmlspecialchars($content['content_id']); ?>">
                        <input type="hidden" name="content_type" value="ugc">
                        <button type="submit">Like</button>
                    </form>
                    <form method="POST" action="comment.php">
                        <input type="hidden" name="content_id" value="<?php echo htmlspecialchars($content['content_id']); ?>">
                        <input type="hidden" name="content_type" value="ugc">
                        <textarea name="comment" placeholder="Add a comment..." required></textarea>
                        <button type="submit">Comment</button>
                    </form>
                    <h3>Comments:</h3>
                    <ul>
                        <?php
                        $stmt = $dbh->prepare("SELECT u.username, cm.comment, cm.created_at FROM comments cm JOIN users u ON cm.user_id = u.id WHERE cm.content_id = ? AND cm.content_type = ? ORDER BY cm.created_at ASC");
                        $stmt->execute([$content['content_id'], 'ugc']);
                        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <?php foreach ($comments as $comment): ?>
                            <li>
                                <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                                <p><small><?php echo htmlspecialchars($comment['created_at']); ?></small></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
