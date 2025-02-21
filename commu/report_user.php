<?php
require 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$reported_user_id = $_GET['user_id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reason = $_POST['reason'] ?? '';

    // Prepare and execute the insert statement
    $stmt = $dbh->prepare("INSERT INTO reports (reporter_id, reported_user_id, reason) VALUES (?, ?, ?)");
    if ($stmt->execute([$user_id, $reported_user_id, $reason])) {
        echo "User reported successfully!";
    } else {
        echo "Failed to report user.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Report User</title>
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
        <h1>Report User</h1>
        <form method="POST" action="report_user.php?user_id=<?php echo htmlspecialchars($reported_user_id); ?>">
            <textarea name="reason" placeholder="Reason for reporting..." required></textarea>
            <button type="submit">Report User</button>
        </form>
    </div>
</body>
</html>
