<?php
require 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$blocked_user_id = $_POST['blocked_user_id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $blocked_user_id) {
    // Prepare and execute the insert statement
    $stmt = $dbh->prepare("INSERT INTO blocked_users (user_id, blocked_user_id) VALUES (?, ?)");
    if ($stmt->execute([$user_id, $blocked_user_id])) {
        echo "User blocked successfully!";
    } else {
        echo "Failed to block user.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Block User</title>
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
        <h1>Block User</h1>
        <form method="POST" action="block_user.php">
            <input type="hidden" name="blocked_user_id" value="<?php echo htmlspecialchars($_GET['blocked_user_id']); ?>">
            <button type="submit">Block User</button>
        </form>
    </div>
</body>
</html>
