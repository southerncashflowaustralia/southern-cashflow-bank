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

// Profile image
$profileImage = !empty($user['profile_pic'])
    ? "../assets/images/" . $user['profile_pic']
    : "../assets/images/default-user.jpg";

// Fixed balance
$displayBalance = 43217043.00;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Profile | Southern Cashflow Finance</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
    margin:0;
    font-family:"Segoe UI", Arial, sans-serif;
    background:#f0f2f7;
    color:#222;
}

/* ===== SIDEBAR ===== */
.sidebar{
    position:fixed;
    top:0; left:0;
    width:240px;
    height:100%;
    background:#1f1f24;
    color:#fff;
    display:flex;
    flex-direction:column;
    padding-top:20px;
    overflow-y:auto;              /* ✅ FIX: allow scrolling */
    padding-bottom:40px;          /* ✅ FIX: show Logout clearly */
}

/* BANK NAME */
.sidebar h2{
    text-align:center;
    margin:0 15px 30px;
    font-size:22px;
    font-weight:700;
    line-height:1.3;
    word-break:break-word;
    letter-spacing:0.5px;
}

/* SIDEBAR LINKS */
.sidebar a{
    display:block;
    padding:15px 22px;
    color:#fff;
    text-decoration:none;
    font-weight:600;
    transition:0.2s;
}
.sidebar a:hover{
    background:#2f2f36;
}

/* MAIN CONTENT */
.main{
    margin-left:240px;
    padding:40px;
}

/* PROFILE CARD */
.profile-card{
    background:#fff;
    max-width:950px;
    margin:auto;
    padding:40px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
}

.profile-header{
    display:flex;
    align-items:center;
    gap:25px;
    margin-bottom:35px;
}

.profile-photo{
    width:160px;
    height:160px;
    border-radius:50%;
    object-fit:cover;
    border:4px solid #1f1f24;
}

.profile-header h1{
    margin:0;
    font-size:32px;
    color:#1f1f24;
}

.profile-header p{
    margin:5px 0 0;
    color:#555;
    font-size:17px;
}

.profile-details table{
    width:100%;
    border-collapse:collapse;
}

.profile-details td{
    padding:14px 0;
    border-bottom:1px solid #eee;
    font-size:16px;
}

.label{
    font-weight:bold;
    color:#555;
    width:220px;
}

/* RESPONSIVE */
@media(max-width:900px){
    .main{ margin-left:0; padding:20px; }
    .profile-card{ padding:25px; }
    .profile-header{ flex-direction:column; text-align:center; }
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
    <a href="profile.php">Profile</a>
    <a href="../auth/logout.php">Logout</a>
</div>

<div class="main">
    <div class="profile-card">
        <div class="profile-header">
            <img src="<?= $profileImage ?>" class="profile-photo">
            <div>
                <h1><?= htmlspecialchars($user['full_name']) ?></h1>
                <p>Account Holder</p>
            </div>
        </div>

        <div class="profile-details">
            <table>
                <tr>
                    <td class="label">Account Number</td>
                    <td><?= htmlspecialchars($user['account_number']) ?></td>
                </tr>
                <tr>
                    <td class="label">Email Address</td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                </tr>
                <tr>
                    <td class="label">Available Balance</td>
                    <td>AUD <?= number_format($displayBalance,2) ?></td>
                </tr>
                <tr>
                    <td class="label">Account Status</td>
                    <td><?= ucfirst($user['status']) ?></td>
                </tr>
                <tr>
                    <td class="label">Member Since</td>
                    <td>15 Feb 2015</td>
                </tr>
            </table>
        </div>
    </div>
</div>

</body>
</html>
