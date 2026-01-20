<?php
session_start();
require_once __DIR__ . "/../config/db.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Check card ID from GET
$card_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($card_id) {
    $stmt = $pdo->prepare("UPDATE cards SET status='frozen' WHERE id=? AND user_id=?");
    $stmt->execute([$card_id, $_SESSION['user_id']]);
    $message = "Card has been frozen successfully.";
} else {
    $message = "Invalid card ID.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Freeze Card</title>
</head>
<body>
    <h2><?= htmlspecialchars($message) ?></h2>
    <a href="cards.php">Back to My Cards</a>
</body>
</html>