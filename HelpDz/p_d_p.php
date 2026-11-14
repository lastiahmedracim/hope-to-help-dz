<?php
session_start();
require_once('../functions/db.php');

header('Content-Type: application/json');

$translations = [
    'fr' => [
        'User not authenticated' => 'Utilisateur non authentifié',
        'Invalid request method' => 'Méthode de requête invalide',
        'You have already paid for access to the draws.' => 'Vous avez déjà payé pour l\'accès aux tirages au sort.',
        'Insufficient funds to participate in the draw.' => 'Fonds insuffisants pour participer au tirage au sort.',
        'Access to draws granted. You can now participate in the draws.' => 'Accès aux tirages accordé. Vous pouvez maintenant participer aux tirages au sort.',
        'You do not have access to the draws. Please pay for access first.' => 'Vous n\'avez pas accès aux tirages au sort. Veuillez payer pour l\'accès d\'abord.',
        'Please select a valid project.' => 'Veuillez sélectionner un projet valide.',
        'You have already participated in the draw for this project.' => 'Vous avez déjà participé au tirage au sort pour ce projet.',
        'You are now participating in the draw for the selected project. Good luck!' => 'Vous participez maintenant au tirage au sort pour le projet sélectionné. Bonne chance !',
        'Unrecognized action.' => 'Action non reconnue.',
        'A database error occurred. Please try again later.' => 'Une erreur de base de données s\'est produite. Veuillez réessayer plus tard.',
        'User not verified' => 'Utilisateur non vérifié'
    ],
    'ar' => [
        'User not authenticated' => 'المستخدم غير مصادق عليه',
        'Invalid request method' => 'طريقة طلب غير صالحة',
        'You have already paid for access to the draws.' => 'لقد دفعت بالفعل مقابل الوصول إلى السحوبات.',
        'Insufficient funds to participate in the draw.' => 'أموال غير كافية للمشاركة في السحب.',
        'Access to draws granted. You can now participate in the draws.' => 'تم منح الوصول إلى السحوبات. يمكنك الآن المشاركة في السحوبات.',
        'You do not have access to the draws. Please pay for access first.' => 'ليس لديك حق الوصول إلى السحوبات. يرجى الدفع للوصول أولاً.',
        'Please select a valid project.' => 'يرجى اختيار مشروع صالح.',
        'You have already participated in the draw for this project.' => 'لقد شاركت بالفعل في السحب لهذا المشروع.',
        'You are now participating in the draw for the selected project. Good luck!' => 'أنت الآن تشارك في السحب للمشروع المختار. حظا سعيدا!',
        'Unrecognized action.' => 'إجراء غير معروف.',
        'A database error occurred. Please try again later.' => 'حدث خطأ في قاعدة البيانات. الرجاء معاودة المحاولة في وقت لاحق.',
        'User not verified' => 'المستخدم غير مُتحقق منه'
    ],
    'en' => [
        'User not authenticated' => 'User not authenticated',
        'Invalid request method' => 'Invalid request method',
        'You have already paid for access to the draws.' => 'You have already paid for access to the draws.',
        'Insufficient funds to participate in the draw.' => 'Insufficient funds to participate in the draw.',
        'Access to draws granted. You can now participate in the draws.' => 'Access to draws granted. You can now participate in the draws.',
        'You do not have access to the draws. Please pay for access first.' => 'You do not have access to the draws. Please pay for access first.',
        'Please select a valid project.' => 'Please select a valid project.',
        'You have already participated in the draw for this project.' => 'You have already participated in the draw for this project.',
        'You are now participating in the draw for the selected project. Good luck!' => 'You are now participating in the draw for the selected project. Good luck!',
        'Unrecognized action.' => 'Unrecognized action.',
        'A database error occurred. Please try again later.' => 'A database error occurred. Please try again later.',
        'User not verified' => 'User not verified'
    ]
];

function translate($key, $lang) {
    global $translations;
    return $translations[$lang][$key] ?? $translations['fr'][$key] ?? $key;
}

if (!isset($_SESSION['user_r_pseudo'])) {
    echo json_encode(['success' => false, 'message' => translate('User not authenticated', $_POST['lang'] ?? 'fr')]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => translate('Invalid request method', $_POST['lang'] ?? 'fr')]);
    exit;
}

$user_r_pseudo = $_SESSION['user_r_pseudo'];
$lang = $_POST['lang'] ?? 'fr';

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        SELECT u.user_id, u.virtual_money, u.is_v, COALESCE(p.is_d, FALSE) as is_d 
        FROM Users u 
        LEFT JOIN Paid p ON u.user_id = p.user_id 
        WHERE u.user_r_pseudo = ?
    ");
    $stmt->execute([$user_r_pseudo]);
    $user = $stmt->fetch();

    if (!$user['is_v']) {
        throw new Exception(translate('User not verified', $lang));
    }

    if (isset($_POST['pay_access']) && $_POST['pay_access'] == '1') {
        if ($user['is_d']) {
            echo json_encode(['success' => false, 'message' => translate('You have already paid for access to the draws.', $lang)]);
            exit;
        }

        if ($user['virtual_money'] < 9500) {
            throw new Exception(translate('Insufficient funds to participate in the draw.', $lang));
        }

        $newBalance = $user['virtual_money'] - 9500;

        $updateStmt = $pdo->prepare("UPDATE Users SET virtual_money = ? WHERE user_id = ?");
        $updateStmt->execute([$newBalance, $user['user_id']]);

        $checkPaidStmt = $pdo->prepare("SELECT * FROM Paid WHERE user_id = ?");
        $checkPaidStmt->execute([$user['user_id']]);
        $paidRecord = $checkPaidStmt->fetch();

        if ($paidRecord) {
            $updatePaidStmt = $pdo->prepare("UPDATE Paid SET is_d = TRUE WHERE user_id = ?");
            $updatePaidStmt->execute([$user['user_id']]);
        } else {
            $insertPaidStmt = $pdo->prepare("INSERT INTO Paid (user_id, is_d) VALUES (?, TRUE)");
            $insertPaidStmt->execute([$user['user_id']]);
        }

        $insertDProfitStmt = $pdo->prepare("INSERT INTO DProfit (user_id, d_amount) VALUES (?, ?)");
        $insertDProfitStmt->execute([$user['user_id'], 9500]);

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => translate('Access to draws granted. You can now participate in the draws.', $lang)]);
        exit;
    } elseif (isset($_POST['selected_project'])) {
        if (!$user['is_d']) {
            throw new Exception(translate('You do not have access to the draws. Please pay for access first.', $lang));
        }

        $selected_project_id = filter_input(INPUT_POST, 'selected_project', FILTER_VALIDATE_INT);

        if (!$selected_project_id) {
            throw new Exception(translate('Please select a valid project.', $lang));
        }

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM Drow WHERE user_id = ? AND project_id = ?");
        $stmt->execute([$user['user_id'], $selected_project_id]);
        $already_participated = $stmt->fetchColumn() > 0;

        if ($already_participated) {
            throw new Exception(translate('You have already participated in the draw for this project.', $lang));
        }

        $stmt = $pdo->prepare("INSERT INTO Drow (user_id, project_id) VALUES (?, ?)");
        $stmt->execute([$user['user_id'], $selected_project_id]);

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => translate('You are now participating in the draw for the selected project. Good luck!', $lang)]);
        exit;
    } else {
        throw new Exception(translate('Unrecognized action.', $lang));
    }
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => translate('A database error occurred. Please try again later.', $lang)]);
    exit;
}