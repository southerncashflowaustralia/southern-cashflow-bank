<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Southern Cashflow Finance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin:0;
            font-family: "Segoe UI", Arial, sans-serif;
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
            top:0;
            width:100%;
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

        /* HERO */
        .hero {
            background:linear-gradient(rgba(0,0,0,.45),rgba(0,0,0,.45)),
                       url('assets/images/bank-hero.jpg') center/cover no-repeat;
            padding:170px 80px 130px;
            color:#fff;
        }

        .hero h1 {
            font-size:44px;
            max-width:650px;
            margin-bottom:15px;
        }

        .hero p {
            font-size:18px;
            max-width:550px;
            margin-bottom:35px;
        }

        .hero-buttons {
            display:flex;
            gap:18px;
            justify-content:flex-start;
        }

        .hero button {
            padding:14px 30px;
            font-size:15px;
            border:none;
            border-radius:6px;
            cursor:pointer;
            font-weight:700;
        }

        .btn-create {
            background:#8b1e3f;
            color:#fff;
        }

        .btn-create:hover {
            background:#751934;
        }

        .btn-access {
            background:#c9a23f;
            color:#1f1f24;
        }

        .btn-access:hover {
            background:#b08f35;
        }

        /* ABOUT */
        .about {
            background:#fff;
            padding:90px 60px;
            display:flex;
            gap:50px;
            align-items:center;
        }

        .about img {
            width:45%;
            border-radius:12px;
            box-shadow:0 6px 18px rgba(0,0,0,.15);
        }

        .about-content {
            width:55%;
        }

        .about-content h2 {
            font-size:32px;
            margin-bottom:20px;
            color:#1f1f24;
        }

        .about-content p {
            font-size:15.5px;
            line-height:1.8;
            margin-bottom:15px;
        }

        /* SERVICES */
        .section {
            background:#f7f7f7;
            padding:90px 60px;
        }

        .section h2 {
            text-align:center;
            font-size:32px;
            margin-bottom:55px;
        }

        .cards {
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
            gap:30px;
        }

        .card {
            background:#fff;
            padding:25px;
            border-radius:14px;
            box-shadow:0 8px 22px rgba(0,0,0,.12);
            text-align:center;
        }

        .card img {
            width:100%;
            border-radius:10px;
            margin-bottom:15px;
        }

        .card h3 {
            margin-bottom:10px;
            color:#1f1f24;
        }

        .card p {
            font-size:14.5px;
            line-height:1.6;
        }

        /* TESTIMONIAL */
        .testimonial {
            background:#1f1f24;
            color:#fff;
            padding:80px 40px;
            text-align:center;
        }

        .testimonial h3 {
            font-size:26px;
            margin-bottom:15px;
        }

        .testimonial p {
            font-size:17px;
            max-width:800px;
            margin:auto;
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
            .about {
                flex-direction:column;
            }
            .about img,
            .about-content {
                width:100%;
            }
            .hero {
                padding:140px 30px 100px;
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
<section class="hero">
    <h1>Banking Built on Trust & Precision</h1>
    <p>
        Secure, modern banking solutions designed for individuals,
        families, and businesses.
    </p>

    <div class="hero-buttons">
        <button class="btn-create" onclick="location.href='createaccount.php'">Create Account</button>
        <button class="btn-access" onclick="location.href='customer/accessaccount.php'">Access Account</button>
    </div>
</section>

<!-- ABOUT -->
<section class="about">
    <img src="assets/images/about-bank.jpg" alt="About Southern Cashflow Finance">
    <div class="about-content">
        <h2>About Southern Cashflow Finance</h2>
        <p>
            Southern Cashflow Finance is a modern financial institution committed to delivering
            secure, transparent, and customer-focused banking solutions. We combine advanced
            digital banking technology with proven financial expertise to serve individuals,
            families, and businesses with confidence and reliability.
        </p>
        <p>
            Our services include transaction accounts, savings solutions, personal and business
            loans, investment products, and digital payment systems — all designed to support
            long-term financial growth.
        </p>
        <p>
            Security, compliance, and integrity are central to everything we do. Our platforms
            are built to protect customer data, ensure transaction accuracy, and operate in
            alignment with international banking standards.
        </p>
        <p>
            At Southern Cashflow Finance, we believe banking should be simple, accessible, and
            empowering. We are more than a bank — we are your long-term financial partner.
        </p>
    </div>
</section>

<!-- SERVICES -->
<section class="section">
    <h2>Our Banking Services</h2>
    <div class="cards">
        <div class="card">
            <img src="assets/images/personal.jpg">
            <h3>Personal Banking</h3>
            <p>Everyday accounts, savings, debit cards, and personal financial tools.</p>
        </div>
        <div class="card">
            <img src="assets/images/business.jpg">
            <h3>Business Banking</h3>
            <p>Business accounts, merchant services, and cashflow management.</p>
        </div>
        <div class="card">
            <img src="assets/images/loans.jpg">
            <h3>Loans & Credit</h3>
            <p>Personal loans, business loans, and structured financing solutions.</p>
        </div>
        <div class="card">
            <img src="assets/images/investment.jpg">
            <h3>Investments</h3>
            <p>Secure investment products designed to grow and protect your wealth.</p>
        </div>
        <div class="card">
            <img src="assets/images/cards.jpg">
            <h3>Cards & Payments</h3>
            <p>Debit cards, digital payments, and secure transaction services.</p>
        </div>
        <div class="card">
            <img src="assets/images/digital.jpg">
            <h3>Digital Banking</h3>
            <p>24/7 online banking with real-time account access and control.</p>
        </div>
    </div>
</section>

<!-- TESTIMONIAL -->
<section class="testimonial">
    <h3>Trusted by Our Customers</h3>
    <p>
        “Southern Cashflow Finance delivers a secure, reliable,
        and professional banking experience that truly stands out.”
    </p>
</section>

<!-- FOOTER -->
<footer>
    <p>&copy; 2026 Southern Cashflow Finance. All rights reserved.</p>
    <p>Email: <a href="mailto:southerncashflowfinance@mail.com">southerncashflowfinance@mail.com</a></p>
</footer>

</body>
</html>
