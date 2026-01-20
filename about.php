<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us | Southern Cashflow Finance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin:0;
            font-family:"Segoe UI", Arial, sans-serif;
            background:#f4f4f4;
            color:#222;
        }

        a { text-decoration:none; }

        /* HEADER */
        header {
            background:#1f1f24;
            color:#fff;
            padding:12px 60px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            position:fixed;
            width:100%;
            top:0;
            z-index:1000;
            box-shadow:0 2px 8px rgba(0,0,0,.3);
        }

        header img {
            height:45px;
        }

        nav {
            display:flex;
            gap:25px;
            align-items:center;
        }

        nav a {
            color:#fff;
            font-weight:600;
            font-size:14px;
            padding:6px 10px;
            border-radius:4px;
        }

        nav a:hover {
            background:#2f2f36;
        }

        .nav-access {
            background:#c9a23f;
            color:#1f1f24;
            font-weight:700;
        }

        .nav-access:hover {
            background:#b08f35;
        }

        .spacer {
            height:75px;
        }

        /* PAGE HERO */
        .page-hero {
            background:linear-gradient(rgba(0,0,0,.5),rgba(0,0,0,.5)),
                       url('assets/images/about-bank.jpg') center/cover no-repeat;
            color:#fff;
            padding:140px 60px 100px;
        }

        .page-hero h1 {
            font-size:40px;
            margin-bottom:10px;
        }

        .page-hero p {
            font-size:18px;
            max-width:700px;
        }

        /* CONTENT */
        .section {
            background:#fff;
            max-width:1000px;
            margin:60px auto;
            padding:60px;
            border-radius:14px;
            box-shadow:0 10px 30px rgba(0,0,0,.12);
        }

        .section h2 {
            font-size:30px;
            margin-bottom:20px;
            color:#1f1f24;
        }

        .section p {
            font-size:15.5px;
            line-height:1.8;
            margin-bottom:18px;
        }

        .values {
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
            gap:25px;
            margin-top:35px;
        }

        .value-card {
            background:#f7f7f7;
            padding:25px;
            border-radius:12px;
            text-align:center;
        }

        .value-card h3 {
            margin-bottom:10px;
            color:#8b1e3f;
        }

        /* FOOTER */
        footer {
            background:#2a2a2a;
            color:#ccc;
            padding:45px 20px;
            text-align:center;
        }

        footer a {
            color:#c9a23f;
            font-weight:600;
        }

        @media(max-width:900px) {
            header {
                padding:12px 30px;
            }
            .page-hero {
                padding:120px 30px 90px;
            }
            .section {
                padding:40px 30px;
                margin:40px 20px;
            }
        }
    </style>
</head>

<body>

<header>
    <img src="assets/images/logo.png" alt="Southern Cashflow Finance">
    <nav>
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="services.php">Services</a>
        <a href="contact.php">Contact</a>
        <a href="customer/accessaccount.php" class="nav-access">Access Account</a>
    </nav>
</header>

<div class="spacer"></div>

<!-- HERO -->
<section class="page-hero">
    <h1>About Southern Cashflow Finance</h1>
    <p>
        A modern financial institution built on trust, security,
        and long-term financial partnership.
    </p>
</section>

<!-- ABOUT CONTENT -->
<section class="section">
    <h2>Who We Are</h2>
    <p>
        Southern Cashflow Finance is a customer-focused financial institution
        committed to delivering secure, transparent, and innovative banking
        solutions. Our services are designed to support individuals, families,
        and businesses through every stage of their financial journey.
    </p>
    <p>
        We combine advanced digital banking infrastructure with experienced
        financial management to provide reliable transaction services,
        savings solutions, lending products, and investment opportunities.
        Our systems are built with security, compliance, and accuracy at
        their core.
    </p>
    <p>
        At Southern Cashflow Finance, we believe banking should be simple,
        accessible, and empowering. We operate with integrity and a long-term
        commitment to financial stability and customer confidence.
    </p>

    <h2>Our Values</h2>
    <div class="values">
        <div class="value-card">
            <h3>Trust & Integrity</h3>
            <p>We operate transparently and responsibly in every financial decision.</p>
        </div>
        <div class="value-card">
            <h3>Security</h3>
            <p>Advanced systems protect customer data and transactions at all times.</p>
        </div>
        <div class="value-card">
            <h3>Innovation</h3>
            <p>Modern banking technology designed for convenience and efficiency.</p>
        </div>
        <div class="value-card">
            <h3>Customer Focus</h3>
            <p>Solutions tailored to individual and business financial needs.</p>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <p>&copy; 2026 Southern Cashflow Finance. All rights reserved.</p>
    <p>Email: <a href="mailto:southerncashflowfinance@mail.com">southerncashflowfinance@mail.com</a></p>
</footer>

</body>
</html>
