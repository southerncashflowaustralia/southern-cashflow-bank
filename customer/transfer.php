<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Select Transfer Type | Southern Cashflow Finance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background:#f2f5f7;
            margin:0;
        }
        .container {
            max-width:600px;
            margin:80px auto;
            background:#fff;
            padding:30px;
            border-radius:12px;
            box-shadow:0 4px 20px rgba(0,0,0,0.1);
        }
        h2 {
            text-align:center;
            color:#0b3a6e;
            margin-bottom:30px;
        }
        .transfer-option {
            display:block;
            padding:18px;
            margin-bottom:15px;
            background:#0b3a6e;
            color:#fff;
            text-decoration:none;
            border-radius:8px;
            font-size:16px;
            font-weight:bold;
            text-align:center;
        }
        .transfer-option:hover {
            opacity:0.9;
        }
        .note {
            text-align:center;
            font-size:13px;
            color:#555;
            margin-top:20px;
        }
        .back {
            display:block;
            text-align:center;
            margin-top:25px;
            text-decoration:none;
            color:#0b3a6e;
            font-weight:bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Select Transfer Type</h2>

    <a href="transfer-local.php" class="transfer-option">
        🏦 Local Bank Transfer
    </a>

    <a href="transfer-international.php" class="transfer-option">
        🌍 International Transfer
    </a>

    <a href="transfer-internal.php" class="transfer-option">
        🔁 Southern Cashflow Finance Transfer
    </a>

    <a href="transfer-crypto.php" class="transfer-option">
        ₿ Crypto / Digital Transfer
    </a>

    <p class="note">
        All transfers are secured and monitored by Southern Cashflow Finance.
    </p>

    <a href="dashboard.php" class="back">← Back to Dashboard</a>
</div>

</body>
</html>
