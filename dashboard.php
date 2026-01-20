<?php 
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Fetch user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: ../auth/login.php");
    exit;
}

// 🔹 Automatically process pending transfers
include __DIR__ . "/process-transfers.php";

// Profile image
$profileImage = !empty($user['profile_pic'])
    ? "../assets/images/" . $user['profile_pic']
    : "../assets/images/default-user.jpg";

// ✅ Fetch transactions including balance_after
$stmt = $pdo->prepare("
    SELECT id, user_id, type, amount, description, status, created_at, balance_after
    FROM transactions
    WHERE user_id = ?
    ORDER BY created_at DESC
    LIMIT 150
");
$stmt->execute([$user['id']]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch unread notifications count
$stmt = $pdo->prepare("SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = ? AND read_status = 0");
$stmt->execute([$user['id']]);
$notification = $stmt->fetch(PDO::FETCH_ASSOC);
$unreadCount = $notification ? $notification['unread_count'] : 0;

$bankEmail = "southerncashflowfinance@mail.com"; // Bank contact email
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard | Southern Cashflow Finance</title>
    <style>
        body { font-family: Arial, sans-serif; margin:0; background:#f2f5f7; color:#000; transition: background 0.3s, color 0.3s; }
        body.dark { background:#1f1f1f; color:#f5f5f5; }
        h2 { color:#0b3a6e; }
        .sidebar { position: fixed; top:0; left:0; width:220px; height:100%; background:#0b3a6e; color:#fff; display:flex; flex-direction:column; padding-top:20px; z-index:1; }
        .sidebar h2 { text-align:center; margin-bottom:30px; font-size:22px; }
        .sidebar a { color:#fff; text-decoration:none; padding:15px 20px; display:block; transition:0.2s; border-radius:5px; margin:2px 10px; }
        .sidebar a:hover { opacity:0.85; }
        .sidebar a.deposit { background:#0d6efd; }
        .sidebar a.withdraw { background:#28a745; }
        .sidebar a.transfer { background:#17a2b8; }
        .sidebar a.loans { background:#ffc107; color:#004466; }
        .sidebar a.settings { background:#0b3a6e; }
        .sidebar a.info { background:#17a2b8; }
        .sidebar a.warning { background:#ffc107; color:#004466; }
        .sidebar a.logout { background:#dc3545; }
        .main { margin-left:220px; padding:30px; }
        .header { display:flex; justify-content:space-between; align-items:center; background:#fff; padding:20px; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.1); margin-bottom:25px; position:relative; }
        .profile-photo { width:80px; height:80px; border-radius:50%; object-fit:cover; border:2px solid #0b3a6e; }
        .settings-icon { width:35px; height:35px; cursor:pointer; margin-left:15px; }
        .settings-panel { position:absolute; top:100%; right:0; background:#fff; border:1px solid #ccc; border-radius:8px; padding:15px; width:220px; display:none; box-shadow:0 5px 15px rgba(0,0,0,0.2); z-index:5; }
        .settings-panel label { display:block; margin:10px 0 5px; font-weight:bold; }
        .settings-panel select, .settings-panel input[type="checkbox"] { width:100%; margin-bottom:10px; }
        body.dark .settings-panel { background:#333; color:#f5f5f5; border-color:#555; }
        .balance-card { background:#0b3a6e; color:white; border-radius:12px; padding:25px; font-size:20px; font-weight:bold; max-width:450px; margin-bottom:30px; }
        .balance-card span { font-size:36px; display:block; }
        .buttons { display:flex; gap:12px; margin-bottom:30px; }
        .buttons button { padding:14px; border:none; border-radius:8px; font-weight:bold; cursor:pointer; background:#0b3a6e; color:white; }
        .scroll-table { max-height:500px; overflow-y:auto; }
        table { width:100%; border-collapse:collapse; background:#fff; }
        th, td { padding:12px; border-bottom:1px solid #ddd; }
        th { background:#0b3a6e; color:white; position:sticky; top:0; }
        .credit { color:green; font-weight:bold; }
        .debit { color:red; font-weight:bold; }
        .notification-badge { background:red; color:white; padding:2px 6px; border-radius:50%; font-size:12px; }

        /* Overlay for frozen/suspended */
        #status-overlay {
            position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.6); display:flex; justify-content:center; align-items:center;
            z-index:9999; display:none;
        }
        #status-overlay .overlay-box {
            background:#fff; padding:40px; border-radius:12px; max-width:500px; text-align:center;
            box-shadow:0 5px 20px rgba(0,0,0,0.3);
        }
        #status-overlay .overlay-box h1 { color:#dc3545; margin-bottom:20px; }
        #status-overlay .overlay-box p { font-size:16px; margin-bottom:30px; }
        #status-overlay .overlay-box a {
            padding:12px 24px; background:#0b3a6e; color:#fff; border:none; border-radius:8px;
            font-weight:bold; text-decoration:none; display:inline-block;
        }
        #status-overlay .overlay-box a:hover { background:#09508b; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Southern Cashflow Finance</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="deposit.php" class="deposit">Deposit</a>
    <a href="withdraw.php" class="withdraw">Withdraw</a>
    <a href="transfer.php" class="transfer">Transfer</a>
    <a href="loans.php" class="loans">Loans</a>
    <a href="profile.php">Profile</a>
    <a href="cards.php">Cards</a>
    <a href="settings.php" class="settings">Settings</a>
    <a href="support.php" class="info">Support / Chat</a>
    <a href="offers.php" class="warning">Offers / Promotions</a>
    <a href="notifications.php" class="info">
        Notifications
        <?php if($unreadCount > 0): ?>
            <span class="notification-badge"><?= $unreadCount ?></span>
        <?php endif; ?>
    </a>
    <a href="../auth/logout.php" class="logout">Logout</a>
</div>

<div class="main">
    <div class="header">
        <h2>Welcome, <?= htmlspecialchars($user['full_name']) ?></h2>
        <div style="display:flex; align-items:center;">
            <img src="<?= $profileImage ?>" class="profile-photo">
            <img src="../assets/images/settings-icon.png" class="settings-icon" id="settings-icon" title="Settings">
            <div class="settings-panel" id="settings-panel">
                <label for="theme-toggle">Dark Mode</label>
                <input type="checkbox" id="theme-toggle">

                <label for="language-select">Language</label>
                <select id="language-select">
                    <option value="en" selected>English</option>
                    <option value="fr">Français</option>
                    <option value="es">Español</option>
                    <option value="de">Deutsch</option>
                </select>

                <label>Other Settings</label>
                <input type="checkbox" id="notifications-toggle"> Email Notifications
            </div>
        </div>
    </div>

    <div class="balance-card">
        Available Balance
        <span>AUD <?= number_format($user['balance'], 2) ?></span>
    </div>

    <div class="buttons">
        <button onclick="location.href='deposit.php'">Deposit</button>
        <button onclick="location.href='withdraw.php'">Withdraw</button>
        <button onclick="location.href='transfer.php'">Transfer</button>
        <button onclick="location.href='loans.php'">Loans</button>
    </div>

    <h2>Recent Transactions</h2>
    <div class="scroll-table">
        <table>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Balance After</th>
            </tr>
            <?php foreach ($transactions as $tx): ?>
            <tr>
                <td><?= htmlspecialchars($tx['created_at']) ?></td>
                <td class="<?= $tx['type'] ?>"><?= ucfirst($tx['type']) ?></td>
                <td><?= htmlspecialchars($tx['description']) ?></td>
                <td class="<?= $tx['type'] ?>"><?= number_format($tx['amount'],2) ?></td>
                <td><?= htmlspecialchars($tx['status'] ?? 'Completed') ?></td>
                <td><?= number_format($tx['balance_after'],2) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<!-- Overlay -->
<div id="status-overlay">
    <div class="overlay-box">
        <h1 id="overlay-title"></h1>
        <p id="overlay-message"></p>
        <a id="help-email" href="#">Need Help? Reactivate Your Account</a>
        <p style="margin-top:15px; font-size:13px;">Contact: <?= $bankEmail ?></p>
    </div>
</div>

<script>
// Settings panel toggle
const settingsIcon = document.getElementById('settings-icon');
const settingsPanel = document.getElementById('settings-panel');
settingsIcon.addEventListener('click', () => {
    settingsPanel.style.display = settingsPanel.style.display === 'block' ? 'none' : 'block';
});

// Theme toggle
const themeToggle = document.getElementById('theme-toggle');
themeToggle.addEventListener('change', () => {
    document.body.classList.toggle('dark', themeToggle.checked);
});

// Language selection (simple immediate effect)
const languageSelect = document.getElementById('language-select');
languageSelect.addEventListener('change', () => {
    alert("Language changed to: " + languageSelect.value);
    // Here you can implement actual translations if needed
});

// Function to check account status via AJAX
function checkStatus() {
    fetch('check-status.php')
        .then(res => res.json())
        .then(data => {
            const overlay = document.getElementById('status-overlay');
            const title = document.getElementById('overlay-title');
            const message = document.getElementById('overlay-message');
            const helpLink = document.getElementById('help-email');

            if(data.status == 'active') {
                overlay.style.display = 'none';
            } else {
                overlay.style.display = 'flex';
                title.innerText = data.status.charAt(0).toUpperCase() + data.status.slice(1);

                if(data.status == 'frozen') {
                    message.innerText = "Dear Customer, your account has been temporarily frozen due to unusual login activity from an unrecognized device or location. Please contact the bank to reactivate it.";
                } else if(data.status == 'suspended') {
                    message.innerText = "Dear Customer, your account has been suspended due to a policy review. Please reach out to the bank for assistance.";
                }

                // Open email client when clicking the help button
                helpLink.href = "mailto:<?= $bankEmail ?>?subject=Account Reactivation Request";
            }
        })
        .catch(err => console.error(err));
}

// Initial check
checkStatus();

// Poll every 5 seconds
setInterval(checkStatus, 5000);
</script>

</body>
</html>
