<?php
// config/db.php

// Database connection details from Render
$host = getenv('DB_HOST');        // e.g., dpg-d5ngnd1r0fns73fijrcg-a.render.com
$db   = getenv('DB_NAME');        // southern_cashflow_bank
$user = getenv('DB_USER');        // southern_cashflow_bank_user
$pass = getenv('DB_PASSWORD');    // your password
$port = getenv('DB_PORT');        // 5432

// Create connection using PDO
try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
