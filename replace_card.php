<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$card_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = "";

if ($card_id) {
    // Generate new card number
    $new_card_number = '5399' . str_pad(rand(0, 999999999999), 12, '0', STR_PAD_LEFT);
    $stmt = $pdo->prepare("UPDATE cards SET card_number=? WHERE id=? AND user_id=?");
    $stmt->execute([$new_card_number, $card_id, $_SESSION['user_id']]);
    $message = "Card has been replaced successfully. New number: **** **** **** " . substr($new_card_number, -4);
} else {
    $message = "Invalid card ID.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Replace Card</title>
</head>
<body>
    <h2><?= htmlspecialchars($message) ?></h2>
    <a href="cards.php">Back to My Cards</a>
</body>
</html>