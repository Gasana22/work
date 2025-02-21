<?php
include 'includes/db.php';
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $bio = $_POST['bio'];
        $profile_picture = $_FILES['profile_picture']['name']; // Handle file upload
        $cover_photo = $_FILES['cover_photo']['name']; // Handle file upload

        // Handle file uploads
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], 'uploads/' . $profile_picture);
        move_uploaded_file($_FILES['cover_photo']['tmp_name'], 'uploads/' . $cover_photo);

        $query = "UPDATE users SET bio='$bio', profile_picture='$profile_picture', cover_photo='$cover_photo' WHERE id='$user_id'";
        if (mysqli_query($conn, $query)) {
            echo "Profile updated!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    $query = "SELECT * FROM users WHERE id='$user_id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
} else {
    echo "Please log in.";
}
?>
<form method="POST" enctype="multipart/form-data">
    <textarea name="bio" placeholder="Bio"><?php echo $user['bio']; ?></textarea>
    <input type="file" name="profile_picture">
    <input type="file" name="cover_photo">
    <button type="submit">Update Profile</button>
</form>
