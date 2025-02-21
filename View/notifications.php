<?php
include 'includes/db.php';
include 'includes/functions.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch notifications
    $query = "SELECT * FROM notifications WHERE user_id='$user_id' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);

    echo "<div class='notifications'>";
    while ($notification = mysqli_fetch_assoc($result)) {
        $is_read = $notification['is_read'] ? 'Read' : 'Unread';
        echo "<div class='notification'>";
        echo "<p>" . $notification['message'] . "</p>";
        echo "<small>Status: " . $is_read . " | " . $notification['created_at'] . "</small>";

        // Mark notification as read
        if (!$notification['is_read']) {
            echo "<form method='POST' action='read_notification.php'>
                    <input type='hidden' name='notification_id' value='" . $notification['id'] . "'>
                    <button type='submit' class='btn'>Mark as Read</button>
                  </form>";
        }

        echo "</div>";
    }
    echo "</div>";
} else {
    echo "Please log in.";
}
?>
