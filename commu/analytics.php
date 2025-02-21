<?php
require 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch analytics data
$stmt = $dbh->prepare("SELECT p.id, p.content, p.media, p.created_at, COUNT(l.id) AS likes, COUNT(c.id) AS comments FROM posts p LEFT JOIN likes l ON p.id = l.post_id LEFT JOIN comments c ON p.id = c.post_id WHERE p.user_id = ? GROUP BY p.id ORDER BY p.created_at DESC");
$stmt->execute([$user_id]);
$analytics = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Analytics and Insights</title>
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
        h1 {
            color: #001f3f;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #001f3f;
            color: #fff;
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
        <h1>Analytics and Insights</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Content</th>
                    <th>Media</th>
                    <th>Created At</th>
                    <th>Likes</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($analytics as $data): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['id']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($data['content'])); ?></td>
                        <td><?php echo $data['media'] ? '<img src="' . htmlspecialchars($data['media']) . '" alt="Media" width="100">' : ''; ?></td>
                        <td><?php echo htmlspecialchars($data['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($data['likes']); ?></td>
                        <td><?php echo htmlspecialchars($data['comments']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
