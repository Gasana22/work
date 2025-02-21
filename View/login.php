<?php
include 'includes/db.php';

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        echo "Login successful!";
    } else {
        echo "Invalid credentials!";
    }
}
?>
<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
