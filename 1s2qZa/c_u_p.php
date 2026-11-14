<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header('Location: index.php');
    exit;
}

require_once('adb.php');
require_once('../functions/hash.php');
require_once('ch_ad_l.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['admin_email'])) {
    $admin_email = $_SESSION['admin_email'];
    $admin_level = getAdminLevel($admin_email, $conn);

    if ($admin_level !== 'main_admin' && $admin_level !== 'sub_admin') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        exit;
    }

    $userRPseudo = $_POST['user_r_pseudo'];
    $newPassword = $_POST['newPassword'];

    if (empty($userRPseudo) || empty($newPassword)) {
        echo json_encode(['success' => false, 'message' => 'User pseudo and new password are required']);
        exit;
    }

    try {
        $salt = generateSalt();
        $pepper = generatePepper();
        $hashedPasswordArray = hashPassword($newPassword, $salt, $pepper);

        $stmt = $conn->prepare("UPDATE Users SET user_password = ?, salt = ?, pepper = ? WHERE user_r_pseudo = ?");
        $stmt->execute([$hashedPasswordArray['hash'], $salt, $pepper, $userRPseudo]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Password changed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found or password not changed']);
        }
    } catch (PDOException $e) {
        error_log("Password change error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred while changing the password: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}