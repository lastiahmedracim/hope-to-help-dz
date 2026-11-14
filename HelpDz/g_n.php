<?php
header('Content-Type: application/json');

try {
    session_start();
    require_once('../functions/db.php');

    if (!isset($_SESSION['user_r_pseudo'])) {
        throw new Exception("User not logged in");
    }

    $user_r_pseudo = $_SESSION['user_r_pseudo'];

    if (!isset($_SESSION['last_notification_cleanup']) || (time() - $_SESSION['last_notification_cleanup'] > 86400)) {
        $expiredResult = deleteExpiredNotifications($pdo);
        $_SESSION['last_notification_cleanup'] = time();
    }

    $userQuery = "SELECT user_id FROM Users WHERE user_r_pseudo = :user_r_pseudo";
    $userStmt = $pdo->prepare($userQuery);
    $userStmt->execute(['user_r_pseudo' => $user_r_pseudo]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("User not found");
    }

    $user_id = $user['user_id'];

    $query = "SELECT DISTINCT n.*, COALESCE(un.is_read, 0) as is_read
              FROM Notifications n
              LEFT JOIN UserNotifications un ON n.id = un.notification_id AND un.user_id = :user_id
              LEFT JOIN NotificationTargets nt ON n.id = nt.notification_id
              WHERE (n.expires_at IS NULL OR n.expires_at > NOW())
                AND n.is_active = 1
                AND (n.target_type = 'all' OR (n.target_type = 'specific' AND nt.user_id = :user_id))
              ORDER BY n.created_at DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $user_id]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $unreadCount = array_reduce($notifications, function($carry, $item) {
        return $carry + ($item['is_read'] ? 0 : 1);
    }, 0);

    echo json_encode([
        'notifications' => $notifications,
        'unreadCount' => $unreadCount
    ]);

} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}

function deleteExpiredNotifications($pdo) {
    $pdo->beginTransaction();

    try {
        $stmtFetchExpired = $pdo->prepare("
            SELECT id, expires_at
            FROM Notifications
            WHERE expires_at IS NOT NULL AND expires_at < NOW()
        ");
        $stmtFetchExpired->execute();
        $expiredNotifications = $stmtFetchExpired->fetchAll(PDO::FETCH_ASSOC);

        // Log or process expired notifications as needed
        foreach ($expiredNotifications as $notification) {
            // Perform any additional checks or logging here
            // For example, logging the expired notifications
            // error_log("Notification expired: ID {$notification['id']}, Expires at {$notification['expires_at']}");

            // Mark the notification as inactive
            $stmtUpdate = $pdo->prepare("
                UPDATE Notifications
                SET is_active = 0
                WHERE id = ?
            ");
            $stmtUpdate->execute([$notification['id']]);
        }

        $deletedCount = count($expiredNotifications);

        $stmtCleanup = $pdo->prepare("
            DELETE FROM UserNotifications
            WHERE notification_id NOT IN (SELECT id FROM Notifications WHERE is_active = 1)
        ");
        $stmtCleanup->execute();
        $cleanedCount = $stmtCleanup->rowCount();

        $pdo->commit();

        return [
            'deleted' => $deletedCount,
            'cleaned' => $cleanedCount
        ];
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}
?>
