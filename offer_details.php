<?php
session_start();
require_once __DIR__ . "/../config/db.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Check if offer id is provided
if (!isset($_GET['id'])) {
    echo "No offer selected.";
    exit;
}

$offer_id = intval($_GET['id']);

// Fetch offer details from DB
$stmt = $pdo->prepare("SELECT * FROM offers WHERE id = ?");
$stmt->execute([$offer_id]);
$offer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$offer) {
    echo "Offer not found.";
    exit;
}

// Handle Apply Now
if (isset($_POST['apply'])) {
    $stmt = $pdo->prepare("INSERT INTO offer_applications (user_id, offer_id, applied_at) VALUES (?, ?, NOW())");
    $stmt->execute([$_SESSION['user_id'], $offer_id]);
    $success = "You have successfully applied for this offer!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($offer['title']) ?> | Southern Cashflow Finance</title>
    <style>
        body { font-family: Arial, sans-serif; margin:30px; background:#f4f6f8; }
        .container { max-width:800px; margin:auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.1);}
        h1 { color:#0b3a6e; }
        p { font-size:16px; line-height:1.5; }
        button { padding:12px 25px; background:#0b3a6e; color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:16px; }
        button:hover { background:#09508b; }
        .success { background:#28a745; color:#fff; padding:10px; border-radius:6px; margin-bottom:15px; }
    </style>
</head>
<body>
<div class="container">
    <?php if (isset($success)) echo "<div class='success'>$success</div>"; ?>
    <h1><?= htmlspecialchars($offer['title']) ?></h1>
    <p><?= htmlspecialchars($offer['description']) ?></p>
    <p><strong>Valid Until:</strong> <?= date("d M Y", strtotime($offer['valid_until'])) ?></p>
    
    <form method="post">
        <button type="submit" name="apply">Apply Now</button>
    </form>
    <p><a href="offers.php">Back to Offers</a></p>
</div>
</body>
</html>
