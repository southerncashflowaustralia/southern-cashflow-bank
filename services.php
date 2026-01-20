<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Services | Southern Cashflow Finance</title>
    <style>
        body { margin:0; font-family: Arial, sans-serif; background:#f4f6f8; }
        header { background:#0b3a6e; color:#fff; padding:20px; display:flex; justify-content:space-between; align-items:center; }
        nav a { color:#fff; margin-left:20px; text-decoration:none; font-weight:bold; }
        nav a:hover { text-decoration:underline; }
        .section { padding:60px 20px; max-width:1200px; margin:auto; }
        h2 { text-align:center; color:#0b3a6e; margin-bottom:40px; }
        .cards { display:flex; flex-wrap:wrap; gap:30px; justify-content:center; }
        .card {
            background:#fff; padding:25px; border-radius:10px; width:280px; box-shadow:0 5px 15px rgba(0,0,0,0.1);
        }
        .card h3 { margin-top:0; color:#0b3a6e; }
        .card p { font-size:14px; line-height:1.6; color:#333; }
    </style>
</head>
<body>

<header>
    <h1>Southern Cashflow Finance</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="services.php">Services</a>
        <a href="contact.php">Contact</a>
    </nav>
</header>

<div class="section">
    <h2>Our Services</h2>
    <div class="cards">
        <div class="card">
            <h3>Personal Accounts</h3>
            <p>Checking and savings accounts with debit cards, online banking, and mobile access for convenience and security.</p>
        </div>
        <div class="card">
            <h3>Loans & Credit</h3>
            <p>Personal, home, car loans, and credit facilities with competitive interest rates and flexible repayment plans.</p>
        </div>
        <div class="card">
            <h3>Investments</h3>
            <p>Managed funds, fixed deposits, and superannuation options to help you grow your wealth over time.</p>
        </div>
        <div class="card">
            <h3>Business Banking</h3>
            <p>Business accounts, merchant services, working capital loans, and advisory support to help businesses thrive.</p>
        </div>
        <div class="card">
            <h3>Digital Banking</h3>
            <p>24/7 online banking, secure transfers, instant notifications, and mobile app for seamless account management.</p>
        </div>
        <div class="card">
            <h3>Customer Support</h3>
            <p>Friendly and reliable support for all banking queries, disputes, and guidance with our dedicated customer service team.</p>
        </div>
    </div>
</div>

</body>
</html>