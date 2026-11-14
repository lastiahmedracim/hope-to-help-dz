<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header('Location: index.php');
    exit;
}
require_once('adb.php');

if (isset($_SESSION['admin_email']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $virtual_money = $_POST['virtual_money'];

    try {
        $conn->beginTransaction();

        $stmtUsers = $conn->prepare("UPDATE Users SET virtual_money = :virtual_money WHERE user_id = :user_id");
        $stmtUsers->bindParam(':virtual_money', $virtual_money, PDO::PARAM_INT);
        $stmtUsers->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmtUsers->execute();

        $stmtPaymentImages = $conn->prepare("UPDATE PaymentImages SET is_v = 0 WHERE user_id = :user_id AND is_v = 1");
        $stmtPaymentImages->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmtPaymentImages->execute();

        $conn->commit();

        echo 'success';
    } catch(PDOException $e) {
        $conn->rollBack();
        echo 'error';
    }
} else {
    echo 'unauthorized';
}
?>