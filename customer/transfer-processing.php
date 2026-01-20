<?php
require_once __DIR__ . "/../config/db.php";

// Set how long before a transfer completes (in seconds)
$processing_time = 300; // 5 minutes

// Get all Processing transactions older than $processing_time
$stmt = $pdo->prepare("
    SELECT * FROM transactions 
    WHERE status = 'Processing' AND created_at <= DATE_SUB(NOW(), INTERVAL ? SECOND)
");
$stmt->execute([$processing_time]);
$pendingTransfers = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($pendingTransfers as $tx) {
    $user_id = $tx['user_id'];
    $amount  = $tx['amount'];

    // Update transaction status to Completed
    $stmt = $pdo->prepare("UPDATE transactions SET status = 'Completed' WHERE id = ?");
    $stmt->execute([$tx['id']]);

    // Update user's balance (add amount back if debit transaction)
    if ($tx['type'] === 'debit') {
        $stmt = $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
        $stmt->execute([$amount, $user_id]);
    } elseif ($tx['type'] === 'credit') {
        $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
        $stmt->execute([$amount, $user_id]);
    }

    // Send notification to user
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message, read_status) VALUES (?, ?, 0)");
    $stmt->execute([
        $user_id,
        "Your transfer of AUD " . number_format($amount,2) . " has been completed."
    ]);
}
