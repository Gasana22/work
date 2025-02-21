<?php
include 'includes/db.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $event_name = $_POST['event_name'];
        $description = $_POST['description'];
        $event_date = $_POST['event_date'];

        $query = "INSERT INTO events (name, description, event_date) VALUES ('$event_name', '$description', '$event_date')";
        if (mysqli_query($conn, $query)) {
            echo "Event created!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    // Fetch events
    $query = "SELECT * FROM events ORDER BY event_date DESC";
    $result = mysqli_query($conn, $query);

    echo "<div class='events'>";
    while ($event = mysqli_fetch_assoc($result)) {
        echo "<div class='event'>";
        echo "<h2>" . $event['name'] . "</h2>";
        echo "<p>" . $event['description'] . "</p>";
        echo "<small>Event Date: " . $event['event_date'] . "</small>";

        // Register for event
        echo "<form method='POST' action='register_event.php'>
                <input type='hidden' name='event_id' value='" . $event['id'] . "'>
                <button type='submit' class='btn'>Register</button>
              </form>";

        echo "</div>";
    }
    echo "</div>";
} else {
    echo "Please log in.";
}
?>
<form method="POST">
    <input type="text" name="event_name" placeholder="Event Name" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <input type="date" name="event_date" required>
    <button type="submit" class="btn">Create Event</button>
</form>
