<?php
require 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $group_name = $_POST['group_name'] ?? '';
    $group_description = $_POST['group_description'] ?? '';

    // Prepare and execute the insert statement
    $stmt = $dbh->prepare("INSERT INTO groups (name, description, creator_id) VALUES (?, ?, ?)");
    if ($stmt->execute([$group_name, $group_description, $user_id])) {
        $group_id = $dbh->lastInsertId();

        // Add the creator to the group members
        $stmt = $dbh->prepare("INSERT INTO group_members (group_id, user_id) VALUES (?, ?)");
        $stmt->execute([$group_id, $user_id]);

        echo "Group created successfully!";
    } else {
        echo "Failed to create group.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Group</title>
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
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, textarea, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #001f3f;
            color: #fff;
            cursor: pointer;
        }
        button:hover {
            background-color: #003366;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create Group</h1>
        <form method="POST" action="create_group.php">
            <label for="group_name">Group Name</label>
            <input type="text" id="group_name" name="group_name" required>
            <label for="group_description">Group Description</label>
            <textarea id="group_description" name="group_description" required></textarea>
            <button type="submit">Create Group</button>
        </form>
    </div>
</body>
</html>
