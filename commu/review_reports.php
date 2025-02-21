<?php
require 'config.php';
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch all reports
$stmt = $dbh->query("SELECT r.id, r.reason, r.created_at, u1.username AS reporter, u2.username AS reported_user FROM reports r JOIN users u1 ON r.reporter_id = u1.id JOIN users u2 ON r.reported_user_id = u2.id ORDER BY r.created_at DESC");
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Review Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #001f3f;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #001f3f;
            color: #fff;
        }
        button {
            background-color: #001f3f;
            color: #fff;
            cursor: pointer;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
        }
        button:hover {
            background-color: #003366;
        }
        .actions {
            display: flex;
            gap: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Review Reports</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Reason</th>
                    <th>Created At</th>
                    <th>Reporter</th>
                    <th>Reported User</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($report['id']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($report['reason'])); ?></td>
                        <td><?php echo htmlspecialchars($report['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($report['reporter']); ?></td>
                        <td><?php echo htmlspecialchars($report['reported_user']); ?></td>
                        <td class="actions">
                            <form method="POST" action="delete_user.php" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($report['reported_user']); ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this user?');">Delete User</button>
                            </form>
                            <form method="POST" action="dismiss_report.php" style="display:inline;">
                                <input type="hidden" name="report_id" value="<?php echo htmlspecialchars($report['id']); ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to dismiss this report?');">Dismiss Report</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
