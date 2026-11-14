<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header('Location: index.php');
    exit;
}

require_once('adb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['admin_email'])) {
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    $targetType = $_POST['targetType'] ?? 'all';
    $expiresAt = $_POST['expiresAt'] ?? '';
    
    if (strlen($subject) < 3 || strlen($message) < 3) {
        echo json_encode(['success' => false, 'message' => 'Le sujet et le message doivent contenir au moins 3 caractères.']);
        exit;
    }

    if (empty($expiresAt)) {
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 year'));
    }

    $stmt = $conn->prepare("SELECT admin_id FROM Admins WHERE admin_email = ?");
    $stmt->execute([$_SESSION['admin_email']]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        echo json_encode(['success' => false, 'message' => 'Administrateur non trouvé.']);
        exit;
    }

    $conn->beginTransaction();

    try {
        $stmt = $conn->prepare("INSERT INTO Notifications (subject, message, admin_id, target_type, expires_at, is_active) VALUES (?, ?, ?, ?, ?, 1)");
        $stmt->execute([$subject, $message, $admin['admin_id'], $targetType, $expiresAt]);
        $notificationId = $conn->lastInsertId();

        if ($targetType === 'specific' && isset($_POST['users'])) {
            $stmt = $conn->prepare("INSERT INTO NotificationTargets (notification_id, user_id) VALUES (?, ?)");
            foreach ($_POST['users'] as $userId) {
                $stmt->execute([$notificationId, $userId]);
            }
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Notification créée avec succès.']);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Échec de la création de la notification: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
}