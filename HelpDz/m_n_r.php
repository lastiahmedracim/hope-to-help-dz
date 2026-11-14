<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    session_start();
    require_once('../functions/db.php');

    if (!isset($_SESSION['user_r_pseudo'])) {
        throw new Exception("User not logged in");
    }

    $user_r_pseudo = $_SESSION['user_r_pseudo'];

    $pdo->beginTransaction();

    $userQuery = "SELECT user_id FROM Users WHERE user_r_pseudo = :user_r_pseudo";
    $userStmt = $pdo->prepare($userQuery);
    $userStmt->execute(['user_r_pseudo' => $user_r_pseudo]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("User not found");
    }

    $user_id = $user['user_id'];

    $query = "INSERT INTO UserNotifications (user_id, notification_id, is_read)
              SELECT :user_id, id, 1 FROM Notifications
              ON DUPLICATE KEY UPDATE is_read = 1";
    $stmt = $pdo->prepare($query);
    if (!$stmt->execute(['user_id' => $user_id])) {
        throw new Exception("Failed to execute statement: " . $stmt->errorInfo()[2]);
    }

    $pdo->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
?>