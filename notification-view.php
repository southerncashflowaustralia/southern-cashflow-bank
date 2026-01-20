<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: notifications.php");
    exit;
}

$id = intval($_GET['id']);

// Fetch the notification
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$note = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$note) {
    header("Location: notifications.php");
    exit;
}

// Mark as read if unread
if ($note['read_status'] == 0) {
    $stmt = $pdo->prepare("UPDATE notifications SET read_status = 1 WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($note['title']) ?> | Southern Cashflow Finance</title>
    <style>
        body { font-family: Arial, sans-serif; margin:0; background:#f2f5f7; color:#000; }
        .container { max-width:800px; margin:50px auto; background:#fff; padding:30px; border-radius:10px; box-shadow:0 5px 20px rgba(0,0,0,0.1); }
        h1 { color:#0b3a6e; margin-bottom:20px; }
        p { font-size:16px; line-height:1.6; }
        a.back { display:inline-block; margin-top:20px; color:#0b3a6e; text-decoration:none; font-weight:bold; }
        a.back:hover { text-decoration:underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($note['title']) ?></h1>
        <p><?= nl2br(htmlspecialchars($note['message'])) ?></p>
        <p class="timestamp">Date: <?= date("d M Y H:i", strtotime($note['created_at'])) ?></p>
        <a href="notifications.php" class="back">← Back to Notifications</a>
    </div>
</body>
</html>
