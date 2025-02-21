<?php
require 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$group_id = $_GET['group_id'] ?? '';

if (!$group_id) {
    echo "No group ID provided.";
    exit;
}

// Fetch group information
$stmt = $dbh->prepare("SELECT name, description FROM groups WHERE id = ?");
$stmt->execute([$group_id]);
$group = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch group members
$members_stmt = $dbh->prepare("SELECT u.id, u.username, u.avatar FROM users u JOIN group_members gm ON u.id = gm.user_id WHERE gm.group_id = ?");
$members_stmt->execute([$group_id]);
$members = $members_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch group messages
$messages_stmt = $dbh->prepare("SELECT gm.id, gm.message, gm.created_at, u.username, u.avatar FROM group_messages gm JOIN users u ON gm.user_id = u.id WHERE gm.group_id = ? ORDER BY gm.created_at ASC");
$messages_stmt->execute([$group_id]);
$messages = $messages_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'] ?? '';

    // Prepare and execute the insert statement
    $stmt = $dbh->prepare("INSERT INTO group_messages (group_id, user_id, message) VALUES (?, ?, ?)");
    if ($stmt->execute([$group_id, $user_id, $message])) {
        header("Location: group_chat.php?group_id=$group_id");
        exit;
    } else {
        echo "Failed to send message.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($group['name']); ?> - Group Chat</title>
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
        .members, .messages {
            margin: 20px 0;
        }
        .member, .message {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
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
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($group['name']); ?> - Group Chat</h1>
        <p><?php echo htmlspecialchars($group['description']); ?></p>
        <div class="members">
            <h2>Members</h2>
            <ul>
                <?php foreach ($members as $member): ?>
                    <li class="member">
                        <img src="<?php echo htmlspecialchars($member['avatar']); ?>" class="avatar" alt="Avatar">
                        <strong><?php echo htmlspecialchars($member['username']); ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="messages">
            <h2>Messages</h2>
            <ul>
                <?php foreach ($messages as $message): ?>
                    <li class="message">
                        <img src="<?php echo htmlspecialchars($message['avatar']); ?>" class="avatar" alt="Avatar">
                        <strong><?php echo htmlspecialchars($message['username']); ?>:</strong> <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                        <small><?php echo htmlspecialchars($message['created_at']); ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <form method="POST" action="group_chat.php?group_id=<?php echo htmlspecialchars($group_id); ?>">
            <textarea name="message" placeholder="Type your message here..." required></textarea>
            <button type="submit">Send</button>
        </form>
    </div>
</body>
</html>
