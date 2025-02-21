<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$conversations_stmt = $dbh->prepare("SELECT DISTINCT u.id, u.username, u.avatar FROM users u JOIN messages m ON (u.id = m.sender_id OR u.id = m.receiver_id) WHERE (m.sender_id = ? OR m.receiver_id = ?) AND u.id != ?");
$conversations_stmt->execute([$user_id, $user_id, $user_id]);
$conversations = $conversations_stmt->fetchAll(PDO::FETCH_ASSOC);

$conversation_user_id = $_GET['user_id'] ?? '';
$messages = [];
$typing_status = false;

if ($conversation_user_id) {
    $messages_stmt = $dbh->prepare("SELECT m.id, m.message, m.created_at, u.username, u.id AS user_id FROM messages m JOIN users u ON m.sender_id = u.id WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?) ORDER BY m.created_at ASC");
    $messages_stmt->execute([$user_id, $conversation_user_id, $conversation_user_id, $user_id]);
    $messages = $messages_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Update read status
    $update_stmt = $dbh->prepare("UPDATE messages SET read_status = 1 WHERE receiver_id = ? AND sender_id = ?");
    $update_stmt->execute([$user_id, $conversation_user_id]);
}

// Fetch typing status
if ($conversation_user_id) {
    $typing_stmt = $dbh->prepare("SELECT typing_status FROM users WHERE id = ?");
    $typing_stmt->execute([$conversation_user_id]);
    $typing_status = $typing_stmt->fetchColumn();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Messages</title>
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
        .typing-indicator {
            color: #666;
            font-size: 0.9em;
        }
        .read-status {
            font-size: 0.8em;
            color: #666;
        }
    </style>
    <script>
        function updateTypingStatus() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update_typing_status.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("user_id=<?php echo $user_id; ?>&status=1");
        }

        function stopTypingStatus() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update_typing_status.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("user_id=<?php echo $user_id; ?>&status=0");
        }

        function markAsRead(messageId) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "mark_as_read.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("message_id=" + messageId);
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Messages</h1>
        <div class="conversations">
            <h2>Conversations</h2>
            <ul>
                <?php foreach ($conversations as $conversation): ?>
                    <li>
                        <img src="<?php echo htmlspecialchars($conversation['avatar']); ?>" class="avatar" alt="Avatar">
                        <a href="messages.php?user_id=<?php echo htmlspecialchars($conversation['id']); ?>"><?php echo htmlspecialchars($conversation['username']); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="chat">
            <h2>Chat</h2>
            <?php if ($conversation_user_id && !empty($messages)): ?>
                <ul>
                    <?php foreach ($messages as $message): ?>
                        <li>
                            <p><strong><?php echo htmlspecialchars($message['username']); ?>:</strong> <?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                            <small class="read-status"><?php echo $message['user_id'] == $user_id && $message['read_status'] == 1 ? 'Read' : ''; ?></small>
                            <small><?php echo htmlspecialchars($message['created_at']); ?></small>
                        </li>
                        <script>markAsRead(<?php echo $message['id']; ?>);</script>
                    <?php endforeach; ?>
                </ul>
                <div class="typing-indicator">
                    <?php echo $typing_status ? 'Typing...' : ''; ?>
                </div>
                <form method="POST" action="send_message.php">
                    <input type="hidden" name="receiver_id" value="<?php echo htmlspecialchars($conversation_user_id); ?>">
                    <textarea name="message" placeholder="Type your message here..." required oninput="updateTypingStatus()" onblur="stopTypingStatus()"></textarea>
                    <button type="submit">Send</button>
                </form>
            <?php else: ?>
                <p>Select a conversation to start chatting.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
