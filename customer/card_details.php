<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$card_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$card = null;

if ($card_id) {
    $stmt = $pdo->prepare("SELECT * FROM cards WHERE id=? AND user_id=?");
    $stmt->execute([$card_id, $_SESSION['user_id']]);
    $card = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Card Details</title>
</head>
<body>
    <?php if ($card): ?>
        <h2>Card Details</h2>
        <p>Card Number: **** **** **** <?= substr($card['card_number'], -4) ?></p>
        <p>Card Type: <?= htmlspecialchars($card['card_type']) ?></p>
        <p>Expiry: <?= htmlspecialchars($card['expiry_month']) ?>/<?= htmlspecialchars($card['expiry_year']) ?></p>
        <p>Balance: AUD <?= number_format($card['balance'],2) ?></p>
        <p>Status: <?= htmlspecialchars($card['status'] ?? 'active') ?></p>
    <?php else: ?>
        <p>Invalid card ID.</p>
    <?php endif; ?>
    <a href="cards.php">Back to My Cards</a>
</body>
</html>