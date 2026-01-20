<?php
session_start();
require_once __DIR__ . "/../config/db.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Fetch user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle message submission
$success = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty(trim($_POST['message']))) {
        try {
            $message = trim($_POST['message']);
            $stmt = $pdo->prepare(
                "INSERT INTO support_messages (user_id, message) VALUES (?, ?)"
            );
            $stmt->execute([$user['id'], $message]);
            $success = "Your secure message has been sent. Our support team will contact you.";
        } catch (PDOException $e) {
            $success = "Unable to send message at this time. Please try again later.";
        }
    } else {
        $success = "Please enter a message before sending.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Support & Secure Messaging | Southern Cashflow Finance</title>
    <style>
        body {
            margin:0;
            font-family: Arial, sans-serif;
            background:#eef3f7;
        }

        /* Sidebar */
        .sidebar {
            position:fixed;
            top:0; left:0;
            width:220px;
            height:100%;
            background:#0b3a6e;
            color:#fff;
            padding-top:20px;
        }
        .sidebar h2 {
            text-align:center;
            margin-bottom:30px;
            font-size:20px;
        }
        .sidebar a {
            display:block;
            padding:15px 20px;
            color:#fff;
            text-decoration:none;
        }
        .sidebar a:hover {
            background:#09508b;
        }

        /* Main */
        .main {
            margin-left:220px;
            padding:40px;
        }

        .support-card {
            max-width:700px;
            margin:auto;
            background:#fff;
            padding:30px;
            border-radius:12px;
            box-shadow:0 5px 20px rgba(0,0,0,0.1);
        }

        .support-card h1 {
            margin-top:0;
            color:#0b3a6e;
            font-size:26px;
        }

        .support-card p {
            color:#555;
            margin-bottom:25px;
        }

        textarea {
            width:100%;
            height:160px;
            padding:15px;
            font-size:15px;
            border-radius:8px;
            border:1px solid #ccc;
            resize:none;
            outline:none;
        }

        textarea:focus {
            border-color:#0b3a6e;
        }

        button {
            margin-top:20px;
            padding:14px 30px;
            font-size:15px;
            font-weight:bold;
            background:#0b3a6e;
            color:#fff;
            border:none;
            border-radius:8px;
            cursor:pointer;
        }

        button:hover {
            background:#09508b;
        }

        .success {
            margin-top:20px;
            padding:12px;
            background:#e6f4ea;
            color:#256029;
            border-radius:6px;
            font-size:14px;
        }
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
    <a href="cards.php">Cards</a>
    <a href="statements.php">Statements</a>
    <a href="support.php">Support / Chat</a>
    <a href="settings.php">Settings</a>
    <a href="../auth/logout.php" style="background:#b00020;">Logout</a>
</div>

<div class="main">
    <div class="support-card">
        <h1>Support & Secure Messaging</h1>
        <p>
            Hello <strong><?= htmlspecialchars($user['full_name']) ?></strong>,  
            send us a secure message and our support team will assist you shortly.
        </p>

        <form method="post">
            <textarea name="message" placeholder="Type your message here..."></textarea>
            <br>
            <button type="submit">Send Secure Message</button>
        </form>

        <?php if (!empty($success)): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>