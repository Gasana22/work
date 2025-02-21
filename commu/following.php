<?php
require 'config.php';
session_start();

// Check if the user ID is provided
if (!isset($_GET['user_id'])) {
    echo "No user ID provided.";
    exit;
}

$user_id = $_GET['user_id'] ?? '';

// Fetch following
$stmt = $dbh->prepare("SELECT u.id, u.username, u.avatar FROM users u JOIN follows f ON u.id = f.following_id WHERE f.follower_id = ?");
$stmt->execute([$user_id]);
$following = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Following</title>
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
        <h1>Following</h1>
        <ul>
            <?php foreach ($following as $followed_user): ?>
                <li>
                    <img src="<?php echo htmlspecialchars($followed_user['avatar']); ?>" class="avatar" alt="Avatar">
                    <a href="view_profile.php?user_id=<?php echo htmlspecialchars($followed_user['id']); ?>"><?php echo htmlspecialchars($followed_user['username']); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
