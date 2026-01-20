<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status'=>'logout']);
    exit;
}

$stmt = $pdo->prepare("SELECT status FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(['status' => $user['status']]);
