<?php
session_start();
require_once __DIR__ . "/../config/db.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle Apply Now click
if (isset($_POST['apply_offer_id'])) {
    $offer_id = intval($_POST['apply_offer_id']);

    // Check if already applied
    $stmt = $pdo->prepare("SELECT * FROM offer_applications WHERE user_id = ? AND offer_id = ?");
    $stmt->execute([$user_id, $offer_id]);
    if ($stmt->rowCount() == 0) {
        // Insert application
        $stmt = $pdo->prepare("INSERT INTO offer_applications (user_id, offer_id, applied_at) VALUES (?, ?, NOW())");
        $stmt->execute([$user_id, $offer_id]);
        $message = "Offer applied successfully!";
    } else {
        $message = "You have already applied for this offer.";
    }
}

// Fetch active offers (status = 'active', today between start_date and valid_until)
$stmt = $pdo->prepare("
    SELECT *
    FROM offers
    WHERE status = 'active'
      AND start_date <= CURDATE()
      AND valid_until >= CURDATE()
    ORDER BY start_date ASC
");
$stmt->execute();
$offers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Offers & Promotions | Southern Cashflow Finance</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f2f5f7; margin:0; }
        .sidebar {
            position: fixed; top:0; left:0; width:220px; height:100%; background:#0b3a6e; color:#fff; padding-top:20px; display:flex; flex-direction:column;
        }
        .sidebar h2 { text-align:center; margin-bottom:30px; font-size:22px; }
        .sidebar a { color:#fff; text-decoration:none; padding:15px 20px; display:block; margin:2px 10px; border-radius:5px; }
        .sidebar a:hover { opacity:0.85; }
        .main { margin-left:220px; padding:30px; }
        h1 { color:#0b3a6e; margin-bottom:20px; }
        .offer-card {
            background:#fff; padding:20px; border-radius:12px; margin-bottom:20px;
            box-shadow:0 5px 15px rgba(0,0,0,0.1);
        }
        .offer-title { font-size:18px; font-weight:bold; color:#0b3a6e; margin-bottom:10px; }
        .offer-desc { font-size:14px; margin-bottom:10px; }
        .offer-dates { font-size:12px; color:#555; margin-bottom:15px; }
        .apply-btn {
            padding:10px 18px; background:#28a745; color:#fff; border:none; border-radius:6px; cursor:pointer;
            transition:0.3s;
        }
        .apply-btn:hover { background:#218838; }
        .message { padding:12px; background:#e0f7e9; color:#056d3f; border-radius:8px; margin-bottom:20px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Southern Cashflow Finance</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="deposit.php">Deposit</a>
    <a href="withdraw.php">Withdraw</a>
    <a href="transfer.php">Transfer</a>
    <a href="loans.php">Loans & Interest</a>
    <a href="profile.php">Profile</a>
    <a href="cards.php">Cards</a>
    <a href="settings.php">Settings</a>
    <a href="support.php">Support / Chat</a>
    <a href="offers.php" style="background:#ffc107; color:#004466;">Offers / Promotions</a>
    <a href="notifications.php">Notifications</a>
    <a href="../auth/logout.php" style="background:#dc3545;">Logout</a>
</div>

<div class="main">
    <h1>Current Offers & Promotions</h1>

    <?php if(isset($message)) echo "<div class='message'>{$message}</div>"; ?>

    <?php if(!empty($offers)): ?>
        <?php foreach($offers as $offer): ?>
            <div class="offer-card">
                <div class="offer-title"><?= htmlspecialchars($offer['title']) ?></div>
                <div class="offer-desc"><?= htmlspecialchars($offer['description']) ?></div>
                <div class="offer-dates">
                    Valid from <?= date("d M Y", strtotime($offer['start_date'])) ?> 
                    to <?= date("d M Y", strtotime($offer['valid_until'])) ?>
                </div>
                <form method="post">
                    <input type="hidden" name="apply_offer_id" value="<?= $offer['id'] ?>">
                    <button type="submit" class="apply-btn">Apply Now</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No current offers at the moment.</p>
    <?php endif; ?>
</div>

</body>
</html>
