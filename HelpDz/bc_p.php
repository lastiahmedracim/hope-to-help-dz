<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
session_start();

if (!isset($_SESSION['user_r_pseudo'])) {
    header('Location: ../index.php');
    exit;
}

require_once('../functions/db.php');
global $pdo;

header('Content-Type: application/json');

function getUserLanguage() {
    return isset($_COOKIE['language']) ? $_COOKIE['language'] : 'fr';
}

$translations = [
    'fr' => [
        'user_not_found' => 'Utilisateur introuvable.',
        'upload_success' => 'Votre image de paiement a été téléchargée avec succès. Cela sera vérifié bientôt.',
        'upload_failure' => 'Échec de l\'enregistrement de l\'image de paiement.',
        'file_upload_failure' => 'Le téléchargement du fichier a échoué.',
        'invalid_request' => 'Requête invalide.',
        'already_sent_demand' => 'Vous avez déjà envoyé une demande de paiement qui est en attente de vérification.',
        'user_not_verified' => 'Utilisateur non vérifié. Veuillez vérifier votre compte avant de procéder au paiement.',
    ],
    'en' => [
        'user_not_found' => 'User not found.',
        'upload_success' => 'Your payment image has been uploaded successfully. It will be verified soon.',
        'upload_failure' => 'Failed to record the payment image.',
        'file_upload_failure' => 'File upload failed.',
        'invalid_request' => 'Invalid request.',
        'already_sent_demand' => 'You have already sent a payment request that is pending verification.',
        'user_not_verified' => 'User not verified. Please verify your account before proceeding with payment.',
    ],
    'ar' => [
        'user_not_found' => 'المستخدم غير موجود.',
        'upload_success' => 'تم تحميل صورة الدفع الخاصة بك بنجاح. سيتم التحقق منها قريبًا.',
        'upload_failure' => 'فشل تسجيل صورة الدفع.',
        'file_upload_failure' => 'فشل تحميل الملف.',
        'invalid_request' => 'طلب غير صالح.',
        'already_sent_demand' => 'لقد أرسلت بالفعل طلب دفع في انتظار التحقق.',
        'user_not_verified' => 'المستخدم غير مُتحقق منه. يرجى التحقق من حسابك قبل المتابعة بالدفع.',
    ],
];

function translate($key, $translations) {
    $lang = getUserLanguage();
    return $translations[$lang][$key] ?? $key;
}

$response = ['status' => 'error', 'message' => ''];

try {
    $userRPseudo = $_SESSION['user_r_pseudo'];
    $stmtUserId = $pdo->prepare("SELECT user_id, is_v FROM Users WHERE user_r_pseudo = ?");
    $stmtUserId->execute([$userRPseudo]);
    $user = $stmtUserId->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $response['message'] = translate('user_not_found', $translations);
    } elseif (!$user['is_v']) {
        $response['message'] = translate('user_not_verified', $translations);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['ccpImage'])) {
        $userId = $user['user_id'];
        $stmtCheck = $pdo->prepare("SELECT * FROM PaymentImages WHERE is_v = 1 AND user_id = ?");
        $stmtCheck->execute([$userId]);

        if ($stmtCheck->rowCount() > 0) {
            $response['message'] = translate('already_sent_demand', $translations);
        } elseif ($_FILES['ccpImage']['error'] == UPLOAD_ERR_NO_FILE) {
            $response['message'] = translate('file_upload_failure', $translations);
        } else {
            $uploadDir = 'uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $originalFileName = basename($_FILES['ccpImage']['name']);
            $uploadFile = $uploadDir . $originalFileName;

            $fileCounter = 1;
            while (file_exists($uploadFile)) {
                $filenameParts = pathinfo($originalFileName);
                $newFilename = $filenameParts['filename'] . '_' . $fileCounter . '.' . $filenameParts['extension'];
                $uploadFile = $uploadDir . $newFilename;
                $fileCounter++;
            }

            if (move_uploaded_file($_FILES['ccpImage']['tmp_name'], $uploadFile)) {
                $stmt = $pdo->prepare("INSERT INTO PaymentImages (image_path, is_v, user_id, uploaded_at) VALUES (?, 1, ?, CURRENT_TIMESTAMP)");
                $stmt->execute([$uploadFile, $userId]);

                if ($stmt->rowCount() > 0) {
                    $response['status'] = 'success';
                    $response['message'] = translate('upload_success', $translations);
                } else {
                    $response['message'] = translate('upload_failure', $translations);
                }
            } else {
                $response['message'] = translate('file_upload_failure', $translations);
            }
        }
    } else {
        $response['message'] = translate('invalid_request', $translations);
    }
} catch (PDOException $e) {
    $response['message'] = "Database error: " . $e->getMessage();
} catch (Exception $e) {
    $response['message'] = "Error: " . $e->getMessage();
}

$output = ob_get_clean();
if (!empty($output)) {
    $response['debug_output'] = $output;
}
echo json_encode($response);