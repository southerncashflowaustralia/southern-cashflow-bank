<?php
session_start();
require_once __DIR__ . "/../config/db.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Handle account status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['new_status'])) {
    $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['new_status'], $_POST['user_id']]);

    // Optional: Send notification to user
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, read_status, created_at) VALUES (?, ?, ?, 0, NOW())");
    $stmt->execute([
        $_POST['user_id'],
        "Account Status Updated",
        "Your account status has been changed to " . $_POST['new_status']
    ]);

    $msg = "Account status updated!";
}

// Fetch all customers
$stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'customer' ORDER BY id DESC");
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard | Southern Cashflow Finance</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <?php if (isset($msg)) echo "<p style='color:green;'>$msg</p>"; ?>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Balance</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php foreach ($customers as $c): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= htmlspecialchars($c['full_name']) ?></td>
            <td><?= htmlspecialchars($c['email']) ?></td>
            <td>AUD <?= number_format($c['balance'],2) ?></td>
            <td><?= ucfirst($c['status']) ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?= $c['id'] ?>">
                    <select name="new_status">
                        <option value="active" <?= $c['status']=='active'?'selected':'' ?>>Active</option>
                        <option value="frozen" <?= $c['status']=='frozen'?'selected':'' ?>>Frozen</option>
                        <option value="suspended" <?= $c['status']=='suspended'?'selected':'' ?>>Suspended</option>
                    </select>
                    <button type="submit">Update</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
