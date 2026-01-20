<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Handle toggle read/unread via AJAX
if (isset($_POST['toggle_id'])) {
    $id = intval($_POST['toggle_id']);
    $stmt = $pdo->prepare("UPDATE notifications SET read_status = IF(read_status=0,1,0) WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    exit('success');
}

// Fetch notifications (sample dates between 2025 and Jan 4, 2026)
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Notifications | Southern Cashflow Finance</title>
    <style>
        body { font-family: Arial, sans-serif; margin:0; background:#f2f5f7; }
        .sidebar { position:fixed; top:0; left:0; width:220px; height:100%; background:#0b3a6e; color:#fff; padding-top:20px; }
        .sidebar h2 { text-align:center; margin-bottom:30px; font-size:22px; }
        .sidebar a { display:block; padding:15px 20px; color:#fff; text-decoration:none; }
        .sidebar a:hover { background:#09508b; }
        .main { margin-left:220px; padding:30px; }
        h1 { color:#0b3a6e; margin-bottom:20px; }
        table { width:100%; border-collapse: collapse; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.05); }
        th, td { padding:12px; text-align:left; font-size:14px; border-bottom:1px solid #ddd; }
        th { background:#0b3a6e; color:#fff; text-transform:uppercase; position:sticky; top:0; }
        tr.unread { background:#e6f0ff; font-weight:bold; }
        tr:hover { background:#f1f1f1; cursor:pointer; }
        td.icon { width:40px; text-align:center; font-size:18px; cursor:pointer; }
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
    <a href="../auth/logout.php">Logout</a>
</div>

<div class="main">
    <h1>Notifications</h1>

    <?php if (!empty($notifications)): ?>
        <table>
            <tr>
                <th></th>
                <th>Title</th>
                <th>Message</th>
                <th>Date</th>
            </tr>
            <?php foreach ($notifications as $note): ?>
                <tr class="<?= $note['read_status'] == 0 ? 'unread' : '' ?>" onclick="window.location='notification-view.php?id=<?= $note['id'] ?>'">
                    <td class="icon" onclick="event.stopPropagation(); toggleRead(<?= $note['id'] ?>, this)">
                        <?= $note['read_status'] == 0 ? '📩' : '✅' ?>
                    </td>
                    <td><?= htmlspecialchars($note['title']) ?></td>
                    <td><?= htmlspecialchars(substr($note['message'],0,50)) ?><?= strlen($note['message'])>50?'...':'' ?></td>
                    <td><?= date("d M Y", strtotime($note['created_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No notifications yet.</p>
    <?php endif; ?>
</div>

<script>
// Toggle read/unread icon via AJAX
function toggleRead(id, el) {
    fetch('', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'toggle_id=' + id
    }).then(res => res.text()).then(res => {
        if (res === 'success') {
            if (el.innerText === '📩') {
                el.innerText = '✅';
                el.closest('tr').classList.remove('unread');
            } else {
                el.innerText = '📩';
                el.closest('tr').classList.add('unread');
            }
        }
    });
}
</script>

</body>
</html>
