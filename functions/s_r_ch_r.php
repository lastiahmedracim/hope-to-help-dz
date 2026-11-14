<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once './db.php';

header('Content-Type: application/json');

function translateMessage($message) {
    $translations = [
        'fr' => [
            'Invalid request data.' => 'Données de requête invalides.',
            'You already have a pending Postal identity number change request. Please wait for it to be processed before submitting a new one.' => 'Vous avez déjà une demande de changement de RIP en attente. Veuillez attendre qu\'elle soit traitée avant d\'en soumettre une nouvelle.',
            'User not found.' => 'Utilisateur non trouvé.',
            'Error while saving the request.' => 'Erreur lors de l\'enregistrement de la demande.',
            'Your Postal identity number change request has been successfully sent.' => 'Votre demande de changement de RIP a été envoyée avec succès.',
            'Database error. Please try again later.' => 'Erreur de base de données. Veuillez réessayer plus tard.',
            'Unauthorized method.' => 'Méthode non autorisée.'
        ],
        'ar' => [
            'Invalid request data.' => 'بيانات الطلب غير صالحة.',
            'You already have a pending Postal identity number change request. Please wait for it to be processed before submitting a new one.' => 'لديك بالفعل طلب تغيير  رقم التعريف البريدي معلق. يرجى الانتظار حتى تتم معالجته قبل تقديم طلب جديد.',
            'User not found.' => 'لم يتم العثور على المستخدم.',
            'Error while saving the request.' => 'خطأ أثناء حفظ الطلب.',
            'Your Postal identity number change request has been successfully sent.' => 'تم إرسال طلب تغيير رقم التعريف البريدي الخاص بك بنجاح.',
            'Database error. Please try again later.' => 'خطأ في قاعدة البيانات. الرجاء المحاولة مرة أخرى لاحقًا.',
            'Unauthorized method.' => 'طريقة غير مصرح بها.'
        ],
        'en' => [
            'Invalid request data.' => 'Invalid request data.',
            'You already have a pending Postal identity number change request. Please wait for it to be processed before submitting a new one.' => 'You already have a pending Postal identity number change request. Please wait for it to be processed before submitting a new one.',
            'User not found.' => 'User not found.',
            'Error while saving the request.' => 'Error while saving the request.',
            'Your Postal identity number change request has been successfully sent.' => 'Your Postal identity number change request has been successfully sent.',
            'Database error. Please try again later.' => 'Database error. Please try again later.',
            'Unauthorized method.' => 'Unauthorized method.'
        ]
    ];

    $lang = $_COOKIE['language'] ?? 'fr';
    return $translations[$lang][$message] ?? $message;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $newRip = $data['newRip'] ?? null;
    $userPseudo = $_SESSION['user_r_pseudo'] ?? null;

    if (!$newRip || !$userPseudo) {
        echo json_encode(['success' => false, 'message' => translateMessage('Invalid request data.')]);
        exit;
    }

    try {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM RipChangeRequests WHERE user_pseudo = :user_pseudo AND status = "pending"');
        $stmt->execute([':user_pseudo' => $userPseudo]);
        $pendingCount = $stmt->fetchColumn();

        if ($pendingCount > 0) {
            echo json_encode(['success' => false, 'message' => translateMessage('You already have a pending Postal identity number change request. Please wait for it to be processed before submitting a new one.')]);
            exit;
        }

        $stmt = $pdo->prepare('SELECT RIP FROM Users WHERE user_r_pseudo = :user_r_pseudo');
        $stmt->execute([':user_r_pseudo' => $userPseudo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception(translateMessage('User not found.'));
        }

        $originalRip = $user['RIP'];

        $stmt = $pdo->prepare('INSERT INTO RipChangeRequests (user_pseudo, new_rip, original_rip, request_date, status) 
                              VALUES (:user_pseudo, :new_rip, :original_rip, NOW(), "pending")');
        $result = $stmt->execute([
            ':user_pseudo' => $userPseudo,
            ':new_rip' => $newRip,
            ':original_rip' => $originalRip
        ]);

        if (!$result) {
            throw new Exception(translateMessage('Error while saving the request.'));
        }

        echo json_encode(['success' => true, 'message' => translateMessage('Your Postal identity number change request has been successfully sent.')]);

    } catch (PDOException $e) {
        error_log('PDO Error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => translateMessage('Database error. Please try again later.')]);
    } catch (Exception $e) {
        error_log('Error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => translateMessage($e->getMessage())]);
    }
} else {
    echo json_encode(['success' => false, 'message' => translateMessage('Unauthorized method.')]);
}
?>