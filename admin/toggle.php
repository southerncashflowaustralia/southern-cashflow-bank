<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['admin_id'])) exit('Not logged in');

if (isset($_POST['user_id'], $_POST['new_status'])) {
    $stmt = $pdo->prepare("UPDATE users SET status=? WHERE id=?");
    $stmt->execute([$_POST['new_status'], $_POST['user_id']]);

    // Optionally send notification
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, read_status, created_at) VALUES (?, ?, ?, 0, NOW())");
    $stmt->execute([
        $_POST['user_id'],
        "Account Status Updated",
        "Your account status has been changed to " . $_POST['new_status']
    ]);

    exit('success');
}
?>
