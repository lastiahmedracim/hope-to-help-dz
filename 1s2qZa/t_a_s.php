<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['admin_email'])) {
    header('Location: index.php');
    exit;
}

function sendJsonResponse($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

try {
    session_start();
    require_once('adb.php');
    require_once('ch_ad_l.php');

    if (!isset($_SESSION['admin_email'])) {
        throw new Exception('Invalid session.');
    }

    $admin_email = $_SESSION['admin_email'];
    $admin_level = getAdminLevel($admin_email, $conn);

    if ($admin_level !== 'main_admin' && $admin_level !== 'sub_admin') {
        throw new Exception('Unauthorized.');
    }

    if (!isset($_POST['user_r_pseudo']) || !isset($_POST['new_status'])) {
        throw new Exception('Missing data.');
    }

    $user_r_pseudo = $_POST['user_r_pseudo'];
    $new_status = $_POST['new_status'] === 'true' ? 1 : 0;

    $stmt = $conn->prepare("UPDATE Users SET is_v = :new_status WHERE user_r_pseudo = :user_r_pseudo");
    $stmt->bindParam(':new_status', $new_status, PDO::PARAM_INT);
    $stmt->bindParam(':user_r_pseudo', $user_r_pseudo, PDO::PARAM_STR);
    $stmt->execute();

    sendJsonResponse(true, 'Updated');
} catch (Exception $e) {
    sendJsonResponse(false, 'Error');
}