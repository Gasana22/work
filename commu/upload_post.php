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
    $content = $_POST['content'] ?? '';
    $media = '';

    // Handle media upload
    if (isset($_FILES['media']) && $_FILES['media']['error'] == 0) {
        $media = 'uploads/' . basename($_FILES['media']['name']);
        move_uploaded_file($_FILES['media']['tmp_name'], $media);
    }

    // Prepare and execute the insert statement
    $stmt = $dbh->prepare("INSERT INTO posts (user_id, content, media) VALUES (?, ?, ?)");
    if ($stmt->execute([$user_id, $content, $media])) {
        echo "Post created successfully!";
    } else {
        echo "Failed to create post.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
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
        <h1>Create Post</h1>
        <form method="POST" action="create_post.php" enctype="multipart/form-data">
            <label for="content">Content</label>
            <textarea id="content" name="content" placeholder="What's on your mind?" required></textarea>
            <label for="media">Media</label>
            <input type="file" id="media" name="media" accept="image/*,video/*">
            <button type="submit">Post</button>
        </form>
    </div>
</body>
</html>
