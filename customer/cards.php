<?php
session_start();
require_once __DIR__ . "/../config/db.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Fetch user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: ../auth/login.php");
    exit;
}

// Determine profile image
$profileImage = !empty($user['profile_pic'])
    ? "../assets/images/" . $user['profile_pic']
    : "../assets/images/default-user.png";

// Fetch all cards for the user
$stmt = $pdo->prepare("SELECT * FROM cards WHERE user_id=?");
$stmt->execute([$_SESSION['user_id']]);
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Cards | Southern CashFlow Finance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin:0;
            background:#f4f6f8;
        }
        .sidebar {
            position: fixed;
            top:0; left:0;
            width:220px; height:100%;
            background:#0b3a6e; color:#fff;
            display:flex; flex-direction:column;
            padding-top:20px;
        }
        .sidebar h2 { text-align:center; margin-bottom:30px; font-size:20px; }
        .sidebar a { color:#fff; text-decoration:none; padding:15px 20px; display:block; transition:0.2s; }
        .sidebar a:hover { background:#09508b; }

        .main { margin-left:220px; padding:30px; }
        h1 { margin-bottom:20px; color:#0b3a6e; }

        .card-container {
            display:flex;
            flex-wrap:wrap;
            gap:30px;
        }

        .card {
            width:320px;
            height:200px;
            border-radius:20px;
            padding:20px;
            color:#fff;
            position:relative;
            background: linear-gradient(135deg,#0b3a6e,#0f5fa8);
            box-shadow:0 8px 25px rgba(0,0,0,0.3);
        }

        .card-type {
            font-size:14px;
            position:absolute;
            top:20px;
            right:20px;
            font-weight:bold;
        }

        .card-number {
            font-size:20px;
            letter-spacing:3px;
            margin-top:50px;
            margin-bottom:15px;
        }

        .card-holder {
            font-size:16px;
            font-weight:bold;
        }

        .card-balance {
            font-size:16px;
            margin-top:10px;
        }

        .card-buttons {
            display:flex;
            justify-content:space-between;
            flex-wrap:wrap;
            gap:10px;
            margin-top:15px;
        }

        .card-buttons button {
            flex:1;
            min-width:140px;
            padding:10px;
            background:#ffcc00;
            color:#004466;
            border:none;
            border-radius:8px;
            cursor:pointer;
            font-size:13px;
            font-weight:bold;
            transition:0.3s;
        }

        .card-buttons button:hover {
            background:#e6b800;
        }

        .no-cards {
            background:#fff;
            padding:20px;
            border-radius:15px;
            box-shadow:0 5px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Southern CashFlow Finance</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="deposit.php">Deposit</a>
    <a href="withdraw.php">Withdraw</a>
    <a href="transfer.php">Transfer</a>
    <a href="loans.php">Loans & Interest</a>
    <a href="profile.php">Profile</a>
    <a href="statements.php">Statements</a>
    <a href="billpay.php">Bill Pay</a>
    <a href="cards.php">Cards</a>
    <a href="investments.php">Investments</a>
    <a href="settings.php">Settings</a>
    <a href="../auth/logout.php">Logout</a>
</div>

<div class="main">
    <h1>My Cards</h1>

    <?php if ($cards): ?>
        <div class="card-container">
            <?php foreach ($cards as $card): ?>
                <div class="card">
                    <div class="card-type"><?= htmlspecialchars($card['card_type']) ?></div>
                    <div class="card-number">
                        **** **** **** <?= substr($card['card_number'], -4) ?>
                    </div>
                    <div class="card-holder"><?= htmlspecialchars($user['full_name']) ?></div>
                    <div class="card-balance">
                        Balance: AUD <?= number_format($card['balance'], 2) ?>
                    </div>
                    <div class="card-buttons">
                        <button onclick="location.href='freeze_card.php?id=<?= $card['id'] ?>'">Freeze Card</button>
                        <button onclick="location.href='card_limits.php?id=<?= $card['id'] ?>'">Card Limits</button>
                        <button onclick="location.href='replace_card.php?id=<?= $card['id'] ?>'">Replace Card</button>
                        <button onclick="location.href='card_details.php?id=<?= $card['id'] ?>'">View Details</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-cards">
            <p>You have no cards yet.</p>
            <button onclick="location.href='createaccount.php'">Open New Account</button>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
