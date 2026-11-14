<?php
session_start();
require_once('../functions/db.php');

header('Content-Type: application/json');

$translations = [
    'fr' => [
        'User not authenticated' => 'Utilisateur non authentifié',
        'Insufficient funds' => 'Fonds insuffisants',
        'Payment successful. You can now add a project.' => 'Paiement effectué avec succès. Vous pouvez maintenant ajouter un projet.',
        'A database error occurred. Please try again later.' => 'Une erreur de base de données s\'est produite. Veuillez réessayer plus tard.',
        'User not verified' => 'Utilisateur non vérifié'
    ],
    'ar' => [
        'User not authenticated' => 'المستخدم غير مصادق عليه',
        'Insufficient funds' => 'أموال غير كافية',
        'Payment successful. You can now add a project.' => 'تم الدفع بنجاح. يمكنك الآن إضافة مشروع.',
        'A database error occurred. Please try again later.' => 'حدث خطأ في قاعدة البيانات. الرجاء معاودة المحاولة في وقت لاحق.',
        'User not verified' => 'المستخدم غير مُتحقق منه'
    ],
    'en' => [
        'User not authenticated' => 'User not authenticated',
        'Insufficient funds' => 'Insufficient funds',
        'Payment successful. You can now add a project.' => 'Payment successful. You can now add a project.',
        'A database error occurred. Please try again later.' => 'A database error occurred. Please try again later.',
        'User not verified' => 'User not verified'
    ]
];

function translate($key, $lang) {
    global $translations;
    return $translations[$lang][$key] ?? $translations['en'][$key] ?? $key;
}

if (!isset($_SESSION['user_r_pseudo'])) {
    echo json_encode(['status' => 'error', 'message' => translate('User not authenticated', $_POST['lang'] ?? 'fr')]);
    exit;
}

$user_r_pseudo = $_SESSION['user_r_pseudo'];
$lang = $_POST['lang'] ?? 'fr';

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT user_id, virtual_money, is_v FROM Users WHERE user_r_pseudo = ?");
    $stmt->execute([$user_r_pseudo]);
    $user = $stmt->fetch();

    if (!$user['is_v']) {
        throw new Exception(translate('User not verified', $lang));
    }

    if ($user['virtual_money'] < 30000) {
        throw new Exception(translate('Insufficient funds', $lang));
    }

    $newBalance = $user['virtual_money'] - 30000;
    $updateStmt = $pdo->prepare("UPDATE Users SET virtual_money = ?, bought = TRUE WHERE user_id = ?");
    $updateStmt->execute([$newBalance, $user['user_id']]);

    $checkPaidStmt = $pdo->prepare("SELECT * FROM Paid WHERE user_id = ?");
    $checkPaidStmt->execute([$user['user_id']]);
    $paidRecord = $checkPaidStmt->fetch();

    if ($paidRecord) {
        $updatePaidStmt = $pdo->prepare("UPDATE Paid SET is_p = TRUE WHERE user_id = ?");
        $updatePaidStmt->execute([$user['user_id']]);
    } else {
        $insertPaidStmt = $pdo->prepare("INSERT INTO Paid (user_id, is_p) VALUES (?, TRUE)");
        $insertPaidStmt->execute([$user['user_id']]);
    }

    $profitAmount = 30000;
    $insertProfitStmt = $pdo->prepare("INSERT INTO PProfit (user_id, P_amount) VALUES (?, ?)");
    $insertProfitStmt->execute([$user['user_id'], $profitAmount]);

    $pdo->commit();
    echo json_encode(['status' => 'success', 'message' => translate('Payment successful. You can now add a project.', $lang)]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => translate('A database error occurred. Please try again later.', $lang)]);
}