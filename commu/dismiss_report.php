<?php
require 'config.php';
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$report_id = $_POST['report_id'] ?? '';

// Prepare and execute the delete statement
$stmt = $dbh->prepare("DELETE FROM reports WHERE id = ?");
if ($stmt->execute([$report_id])) {
    echo "Report dismissed successfully!";
} else {
    echo "Failed to dismiss report.";
}
?>
