<?php
include 'includes/db.php';
include 'includes/functions.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $receiver_id = $_POST['receiver_id'];
        $message = $_POST['message'];

        $query = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ('$user_id', '$receiver_id', '$message')";
        if (mysqli_query($conn, $query)) {
            // Add notification for the receiver
            add_notification($receiver_id, "You have received a new message from User ID: $user_id");
            echo "Message sent!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    // Fetch messages
    $query = "SELECT * FROM messages WHERE sender_id='$user_id' OR receiver_id='$user_id' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);

    echo "<div class='messages'>";
    while ($msg = mysqli_fetch_assoc($result)) {
        echo "<div class='message'>";
        echo "<p>" . $msg['message'] . "</p>";
        echo "<small>From User ID: " . $msg['sender_id'] . " to User ID: " . $msg['receiver_id'] . " on " . $msg['created_at'] . "</small>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "Please log in.";
}
?>
<form method="POST">
    <input type="hidden" name="receiver_id" value="">
    <textarea name="message" placeholder="Enter your message"></textarea>
    <button type="submit" class="btn">Send</button>
</form>
