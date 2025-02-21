<?php
include 'includes/db.php';
require 'vendor/autoload.php';

use RobThree\Auth\TwoFactorAuth;

$tfa = new TwoFactorAuth('MyApp');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    if (mysqli_query($conn, $query)) {
        echo "Registration successful!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>
