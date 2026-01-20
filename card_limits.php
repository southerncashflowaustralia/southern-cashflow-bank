<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$card_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch current card limits
$limits = null;
if ($card_id) {
    $stmt = $pdo->prepare("SELECT * FROM cards WHERE id=? AND user_id=?");
    $stmt->execute([$card_id, $_SESSION['user_id']]);
    $limits = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Card Limits</title>
</head>
<body>
    <h2>Card Limits</h2>
    <?php if ($limits): ?>
        <p>Daily Withdrawal Limit: AUD <?= number_format($limits['withdrawal_limit'] ?? 1000,2) ?></p>
        <p>Daily Purchase Limit: AUD <?= number_format($limits['purchase_limit'] ?? 5000,2) ?></p>
        <p>Monthly Limit: AUD <?= number_format($limits['monthly_limit'] ?? 20000,2) ?></p>
    <?php else: ?>
        <p>Invalid card ID.</p>
    <?php endif; ?>
    <a href="cards.php">Back to My Cards</a>
</body>
</html>