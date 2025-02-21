<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch unread notifications
$stmt = $dbh->prepare("SELECT n.id, n.message, n.created_at, u.username FROM notifications n JOIN users u ON n.sender_id = u.id WHERE n.receiver_id = ? AND n.read_status = 0 ORDER BY n.created_at DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mark notifications as read
$stmt = $dbh->prepare("UPDATE notifications SET read_status = 1 WHERE receiver_id = ?");
$stmt->execute([$user_id]);

echo json_encode($notifications);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notifications</title>
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
        .notification-container {
            position: fixed;
            top: 10px;
            right: 10px;
            width: 300px;
            max-height: 400px;
            overflow-y: auto;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
            z-index: 1000;
        }
        .notification {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .notification:last-child {
            border-bottom: none;
        }
        .notification small {
            display: block;
            color: #666;
            margin-top: 5px;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function checkNotifications() {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "notification.php", true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var notifications = JSON.parse(xhr.responseText);
                        var notificationContainer = document.getElementById("notificationContainer");
                        notificationContainer.innerHTML = "";
                        
                        notifications.forEach(function(notification) {
                            var notificationItem = document.createElement("div");
                            notificationItem.className = "notification";
                            notificationItem.innerHTML = `
                                <strong>${notification.username}:</strong> ${notification.message}
                                <small>${notification.created_at}</small>
                            `;
                            notificationContainer.appendChild(notificationItem);
                        });
                    }
                };
                xhr.send();
            }

            setInterval(checkNotifications, 5000); // Check for notifications every 5 seconds
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Notifications</h1>
        <div id="notificationContainer" class="notification-container"></div>
    </div>
</body>
</html>




