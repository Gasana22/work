<?php
require 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$receiver_id = $_SESSION['user_id'];

// Prepare and execute the select statement
$stmt = $dbh->prepare("SELECT m.id, m.message, m.created_at, u.username AS sender_username FROM messages m JOIN users u ON m.sender_id = u.id WHERE m.receiver_id = ? ORDER BY m.created_at DESC");
$stmt->execute([$receiver_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inbox</title>
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
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        p {
            margin: 0;
        }
        small {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Inbox</h1>
        <ul>
            <?php foreach ($messages as $message): ?>
                <li>
                    <p><strong><?php echo htmlspecialchars($message['sender_username']); ?>:</strong> <?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                    <p><small><?php echo htmlspecialchars($message['created_at']); ?></small></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
