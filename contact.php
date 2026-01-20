<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact Us | Southern Cashflow Finance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { margin:0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:#f0f4f8; color:#333; }
        
        header { 
            background: linear-gradient(90deg, #0066cc, #004080);
            color:#fff; padding:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; 
        }
        header h1 { margin:0; font-size:26px; }
        nav a { color:#fff; margin-left:20px; text-decoration:none; font-weight:bold; }
        nav a:hover { text-decoration:underline; }

        .section { 
            padding:50px 20px; 
            max-width:700px; 
            margin:50px auto; 
            background: linear-gradient(145deg, #e6f7ff, #cceeff);
            border-radius:15px; 
            box-shadow:0 5px 25px rgba(0,0,0,0.1); 
        }

        h2 { color:#003366; font-size:30px; margin-bottom:25px; text-align:center; }
        p { font-size:16px; line-height:1.7; margin-bottom:15px; }
        a { color:#003366; text-decoration:none; font-weight:bold; }
        a:hover { text-decoration:underline; }

        .contact-item { display:flex; align-items:center; margin-bottom:18px; font-size:16px; }
        .contact-item span { font-weight:bold; margin-right:10px; width:130px; display:inline-block; }
        .contact-item i { margin-right:8px; color:#003366; }

        @media (max-width: 500px) {
            header { flex-direction: column; gap:10px; text-align:center; }
            nav { margin-top:10px; }
        }
    </style>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
    <h2>Contact Us</h2>
    <p>If you have any questions or need support, we’re here to help. Reach out using the contact details below during business hours.</p>

    <div class="contact-item">
        <i class="fas fa-envelope"></i>
        <span>Email:</span>
        <a href="mailto:southerncashflowfinance@mail.com">southerncashflowfinance@mail.com</a>
    </div>

    <div class="contact-item">
        <i class="fas fa-clock"></i>
        <span>Support Hours:</span>
        Mon–Fri: 9:00 AM – 5:00 PM (ACST)
    </div>

    <p>We aim to respond to all inquiries within 1–2 business days.</p>
</div>

</body>
</html>