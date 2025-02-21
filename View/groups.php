<?php
include 'includes/db.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $group_name = $_POST['group_name'];
        $description = $_POST['description'];

        $query = "INSERT INTO groups (name, description) VALUES ('$group_name', '$description')";
        if (mysqli_query($conn, $query)) {
            echo "Group created!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    // Fetch groups
    $query = "SELECT * FROM groups ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);

    echo "<div class='groups'>";
    while ($Dgroup = mysqli_fetch_assoc($result)) {
        echo "<div class='group'>";
        echo "<h2>" . $Dgroup['name'] . "</h2>";
        echo "<p>" . $Dgroup['description'] . "</p>";
        echo "<small>Created on " . $Dgroup['created_at'] . "</small>";

        // Join group
        echo "<form method='POST' action='join_group.php'>
                <input type='hidden' name='group_id' value='" . $group['id'] . "'>
                <button type='submit' class='btn'>Join Group</button>
              </form>";

        echo "</div>";
    }
    echo "</div>";
} else {
    echo "Please log in.";
}
?>
<form method="POST">
    <input type="text" name="group_name" placeholder="Group Name" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <button type="submit" class="btn">Create Group</button>
</form>
