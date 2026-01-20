<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM loans WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user['id']]);
$loans = $stmt->fetchAll();

// Helper function to calculate approximate monthly repayment
function calculateMonthlyRepayment($principal, $interest_rate, $term_months) {
    $monthly_rate = $interest_rate / 100 / 12;
    if ($monthly_rate == 0) return $principal / $term_months;
    return $principal * $monthly_rate / (1 - pow(1 + $monthly_rate, -$term_months));
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Loans & Interest | Southern Cashflow Finance</title>
<style>
body { margin:0; font-family:Arial; background:#f4f6f8; }
.main { margin-left:220px; padding:40px; }
.card { background:#fff; padding:25px; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.1); margin-bottom:25px; }
h1 { margin-top:0; color:#0b3a6e; }
table { width:100%; border-collapse:collapse; margin-top:15px; }
th, td { padding:12px; border-bottom:1px solid #eee; font-size:14px; }
th { background:#0b3a6e; color:#fff; text-align:left; }
.status-active { color:green; font-weight:bold; }
.status-closed { color:#777; font-weight:bold; }
.btn { padding:12px 18px; background:#0b3a6e; color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:bold; }
.btn:hover { background:#09508b; }
</style>
</head>
<body>

<?php include __DIR__ . "/sidebar.php"; ?>

<div class="main">

<div class="card">
    <h1>Loans & Interest</h1>
    <p>Manage your loans, repayments, and interest details.</p>
</div>

<div class="card">
<h3>Your Loans</h3>

<?php if ($loans): ?>
<table>
<tr>
    <th>Loan Type</th>
    <th>Balance</th>
    <th>Interest</th>
    <th>Repayment</th>
    <th>Next Due</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php foreach ($loans as $loan): 
    $monthly_repayment = calculateMonthlyRepayment($loan['principal'], $loan['interest_rate'], $loan['term_months']);
    $next_due = date("Y-m-d", strtotime("+1 month")); // placeholder, can improve later
?>
<tr>
    <td><?= htmlspecialchars($loan['loan_type']) ?></td>
    <td>AUD <?= number_format($loan['outstanding_balance'],2) ?></td>
    <td><?= number_format($loan['interest_rate'],2) ?>%</td>
    <td>AUD <?= number_format($monthly_repayment,2) ?> / Monthly</td>
    <td><?= date("d M Y", strtotime($next_due)) ?></td>
    <td class="status-<?= strtolower($loan['status']) ?>"><?= ucfirst($loan['status']) ?></td>
    <td>
        <button class="btn" onclick="location.href='repay-loan.php?id=<?= $loan['id'] ?>'">
            Make Repayment
        </button>
    </td>
</tr>
<?php endforeach; ?>

</table>
<?php else: ?>
<p>You have no active loans.</p>
<?php endif; ?>
</div>

</div>
</body>
</html>
