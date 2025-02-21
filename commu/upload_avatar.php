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
    if (!empty($_FILES['avatar']['name'])) {
        $target_dir = "uploads/avatars/";
        $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
            // Prepare and execute the update statement
            $stmt = $dbh->prepare("UPDATE users SET avatar = ? WHERE id = ?");
            if ($stmt->execute([$target_file, $user_id])) {
                echo "Avatar uploaded successfully!";
            } else {
                echo "Failed to upload avatar.";
            }
        } else {
            echo "Failed to move uploaded file.";
        }
    } else {
        echo "No file selected.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Avatar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #001f3f;
        }
        input, button {
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
        <h1>Upload Avatar</h1>
        <form method="POST" action="upload_avatar.php" enctype="multipart/form-data">
            <input type="file" name="avatar" accept="image/*" required>
            <button type="submit">Upload</button>
        </form>
    </div>
</body>
</html>
