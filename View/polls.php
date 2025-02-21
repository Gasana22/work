<?php
include 'includes/db.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_poll'])) {
        $question = $_POST['question'];

        $query = "INSERT INTO polls (question) VALUES ('$question')";
        if (mysqli_query($conn, $query)) {
            echo "Poll created!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    // Fetch polls
    $query = "SELECT * FROM polls ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);

    echo "<div class='polls'>";
    while ($poll = mysqli_fetch_assoc($result)) {
        echo "<div class='poll'>";
        echo "<h2>" . $poll['question'] . "</h2>";

        // Fetch poll responses
        $poll_id = $poll['id'];
        $response_query = "SELECT * FROM poll_responses WHERE poll_id='$poll_id'";
        $response_result = mysqli_query($conn, $response_query);
        $responses = mysqli_fetch_all($response_result, MYSQLI_ASSOC);

        // Display poll responses
        echo "<ul>";
        foreach ($responses as $response) {
            echo "<li>User ID: " . $response['user_id'] . " responded: " . $response['response'] . "</li>";
        }
        echo "</ul>";

        // Respond to poll
        echo "<form method='POST' action='respond_poll.php'>
                <input type='hidden' name='poll_id' value='$poll_id'>
                <textarea name='response' placeholder='Your response'></textarea>
                <button type='submit' class='btn'>Submit Response</button>
              </form>";

        echo "</div>";
    }
    echo "</div>";
} else {
    echo "Please log in.";
}
?>
<form method="POST">
    <input type="hidden" name="create_poll" value="1">
    <textarea name="question" placeholder="Enter your poll question" required></textarea>
    <button type="submit" class="btn">Create Poll</button>
</form>
