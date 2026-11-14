<?php
require_once('../functions/db.php');

session_start();
$user_r_pseudo = $_SESSION['user_r_pseudo'];

$pdo = $GLOBALS['pdo'];

$stmt = $pdo->prepare("SELECT user_name, user_last_name, wilaya, city, district, user_address, phone_number FROM Users WHERE user_r_pseudo = :user_r_pseudo");
$stmt->execute([':user_r_pseudo' => $user_r_pseudo]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    header('Content-Type: application/json');
    echo json_encode($user);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'User not found']);
}