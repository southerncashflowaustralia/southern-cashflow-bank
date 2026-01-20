<?php
session_start();
require_once __DIR__ . "/config/db.php";

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $dob = trim($_POST['dob']);
    $address = trim($_POST['address']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $account_type = $_POST['account_type'];
    $gender = $_POST['gender'];
    $country = $_POST['country'];

    $profile_pic = 'default-user.jpg';
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
        $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        $profile_pic = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], __DIR__ . "/assets/images/" . $profile_pic);
    }

    if (!$full_name) $errors[] = "Full name is required.";
    if (!$email) $errors[] = "Email is required.";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match.";

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $username]);
    if ($stmt->fetch()) $errors[] = "Email or username already exists.";

    if (!$errors) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (full_name,email,phone,dob,address,username,password,account_type,gender,country,profile_pic,balance,created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,0,NOW())");
        $stmt->execute([$full_name,$email,$phone,$dob,$address,$username,$hashed_password,$account_type,$gender,$country,$profile_pic]);
        $success = true;
        header("refresh:3;url=auth/login.php");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Account | Southern Cashflow Finance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        html, body { margin:0; padding:0; height:100%; font-family: Arial, sans-serif; background: linear-gradient(135deg,#6dd5fa,#ffffff); }
        body { display:flex; justify-content:center; align-items:center; }

        .container {
            background:white; 
            padding:20px 30px;
            border-radius:12px; 
            box-shadow:0 5px 20px rgba(0,0,0,0.2); 
            width:100%; max-width:550px;
        }

        h1 { text-align:center; color:#0b3a6e; margin-bottom:20px; font-size:28px; }
        form { display:flex; flex-wrap:wrap; gap:10px; }
        input, select { padding:10px; font-size:14px; border-radius:5px; border:1px solid #ccc; flex:1 1 48%; }
        input[type="file"] { flex:1 1 100%; }
        button { padding:12px; font-size:16px; background:#0b3a6e; color:#fff; border:none; border-radius:8px; cursor:pointer; flex:1 1 100%; margin-top:10px; }
        button:hover { background:#09508b; }
        .error { color:red; width:100%; margin-bottom:10px; }
        .success { color:green; text-align:center; font-weight:bold; }
        .login-link { text-align:center; margin-top:10px; width:100%; }

        @media (max-width: 500px) {
            input, select { flex:1 1 100%; }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Southern Cashflow Finance</h1>

    <?php if ($errors): ?>
        <div class="error">
            <?php foreach ($errors as $err) echo "<p>$err</p>"; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success">
            Account successfully created! Redirecting to Access page...
        </div>
    <?php else: ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="text" name="phone" placeholder="Phone Number">
            <input type="date" name="dob" placeholder="Date of Birth">
            <input type="text" name="address" placeholder="Address">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <select name="account_type">
                <option value="Checking">Checking</option>
                <option value="Savings">Savings</option>
                <option value="Business">Business</option>
            </select>
            <select name="gender">
                <option value="">Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            <input type="text" name="country" placeholder="Country">
            <input type="file" name="profile_pic">
            <button type="submit">Create Account</button>
            <div class="login-link">
                Already have an account? <a href="auth/login.php">Access</a>
            </div>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
