<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Fetch user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: ../auth/login.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $beneficiary_name = trim($_POST['beneficiary_name']);
    $bank_name        = trim($_POST['bank_name']);
    $country          = trim($_POST['country']);
    $iban             = trim($_POST['iban']);
    $swift            = trim($_POST['swift']);
    $amount           = floatval($_POST['amount']);
    $description      = trim($_POST['description']);

    if ($amount <= 0) {
        $message = "Invalid transfer amount.";
    } else {

        // Insert transaction (PENDING)
        $stmt = $pdo->prepare("
            INSERT INTO transactions 
            (user_id, type, amount, description, status, created_at)
            VALUES (?, 'debit', ?, ?, 'Processing', NOW())
        ");

        $stmt->execute([
            $user['id'],
            $amount,
            "International Transfer to $beneficiary_name ($bank_name, $country) - $description"
        ]);

        // Notification
        $stmt = $pdo->prepare("
            INSERT INTO notifications (user_id, message, read_status)
            VALUES (?, ?, 0)
        ");
        $stmt->execute([
            $user['id'],
            "International transfer of AUD " . number_format($amount,2) . " is processing."
        ]);

        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>International Transfer | Southern Cashflow Finance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background:#f2f5f7;
            margin:0;
        }
        .container {
            max-width:650px;
            margin:60px auto;
            background:#fff;
            padding:35px;
            border-radius:12px;
            box-shadow:0 4px 20px rgba(0,0,0,0.1);
        }
        h2 {
            text-align:center;
            color:#0b3a6e;
            margin-bottom:30px;
        }
        label {
            font-weight:bold;
            display:block;
            margin-top:15px;
        }
        input, textarea, select {
            width:100%;
            padding:12px;
            margin-top:6px;
            border-radius:6px;
            border:1px solid #ccc;
            font-size:14px;
        }
        textarea { resize:none; }
        button {
            margin-top:25px;
            width:100%;
            padding:14px;
            background:#0b3a6e;
            color:#fff;
            border:none;
            border-radius:8px;
            font-size:16px;
            font-weight:bold;
            cursor:pointer;
        }
        button:hover { opacity:0.9; }
        .back {
            display:block;
            text-align:center;
            margin-top:20px;
            text-decoration:none;
            font-weight:bold;
            color:#0b3a6e;
        }
        .note {
            font-size:13px;
            color:#555;
            margin-top:15px;
            text-align:center;
        }
        .error {
            background:#ffe0e0;
            padding:10px;
            border-radius:6px;
            color:#900;
            margin-bottom:15px;
            text-align:center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🌍 International Transfer</h2>

    <?php if ($message): ?>
        <div class="error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">

        <label>Beneficiary Full Name</label>
        <input type="text" name="beneficiary_name" required>

        <label>Beneficiary Bank Name</label>
        <input type="text" name="bank_name" required>

        <label>Bank Country</label>
        <input type="text" name="country" required>

        <label>IBAN / Account Number</label>
        <input type="text" name="iban" required>

        <label>SWIFT / BIC Code</label>
        <input type="text" name="swift" required>

        <label>Transfer Amount (AUD)</label>
        <input type="number" name="amount" step="0.01" required>

        <label>Transfer Description</label>
        <textarea name="description" rows="3" placeholder="School fees, invoice payment, family support..." required></textarea>

        <button type="submit">Submit International Transfer</button>
    </form>

    <p class="note">
        Transfers are subject to compliance checks and may take 1–5 business days.
    </p>

    <a href="transfer.php" class="back">← Back to Transfer Options</a>
</div>

</body>
</html>
