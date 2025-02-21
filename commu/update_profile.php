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
    $bio = $_POST['bio'] ?? '';
    $social_links = json_encode($_POST['social_links'] ?? []);
    $profile_picture = '';

    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture = $target_file;
        }
    }

    // Prepare and execute the insert or update statement
    $stmt = $dbh->prepare("INSERT INTO profiles (user_id, bio, profile_picture, social_links) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE bio = VALUES(bio), profile_picture = VALUES(profile_picture), social_links = VALUES(social_links)");
    if ($stmt->execute([$user_id, $bio, $profile_picture, $social_links])) {
        echo "Profile updated successfully!";
    } else {
        echo "Failed to update profile.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Profile</title>
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
        <h1>Update Profile</h1>
        <form method="POST" action="update_profile.php" enctype="multipart/form-data">
            <textarea name="bio" placeholder="Tell us about yourself"></textarea>
            <input type="file" name="profile_picture">
            <input type="text" name="social_links[facebook]" placeholder="Facebook URL">
            <input type="text" name="social_links[twitter]" placeholder="Twitter URL">
            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
