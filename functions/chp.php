<?php
require_once('db.php');

header('Content-Type: application/json');

function getUserLanguage() {
    return $_COOKIE['language'] ?? 'fr';
}

$translations = [
    'fr' => [
        'invitation_code_left_exists' => 'Le code d\'invitation Gauche existe déjà.',
        'invitation_code_right_exists' => 'Le code d\'invitation Droite existe déjà.',
        'invalid_invitation_code' => 'Nous n\'avons pas pu localiser le code d\'invitation que vous avez fourni. Veuillez utiliser le code de votre ami ou vérifier que vous avez bien saisi le bon code.',
        'validation_passed' => 'Validation réussie.',
        'database_error' => 'Erreur de base de données: ',
        'invalid_data' => 'Données invalides.'
    ],
    'en' => [
        'invitation_code_left_exists' => 'The Left invitation code already exists.',
        'invitation_code_right_exists' => 'The Right invitation code already exists.',
        'invalid_invitation_code' => 'We couldn\'t locate the invitation code you provided. Please use your friend\'s code or verify that you entered the correct code.',
        'validation_passed' => 'Validation passed.',
        'database_error' => 'Database error: ',
        'invalid_data' => 'Invalid data.'
    ],
    'ar' => [
        'invitation_code_left_exists' => 'رمز الدعوة الأيسر موجود بالفعل.',
        'invitation_code_right_exists' => 'رمز الدعوة الأيمن موجود بالفعل.',
        'invalid_invitation_code' => 'لم نتمكن من تحديد موقع رمز الدعوة الذي قدمته. يرجى استخدام رمز صديقك أو التحقق من إدخال الرمز الصحيح.',
        'validation_passed' => 'نجح التحقق.',
        'database_error' => 'خطأ في قاعدة البيانات: ',
        'invalid_data' => 'بيانات غير صالحة.'
    ]
];

function translate($key) {
    global $translations;
    $lang = getUserLanguage();
    return $translations[$lang][$key] ?? $translations['en'][$key];
}

if (isset($_POST['user_email'], $_POST['phone_number'], $_POST['user_l_pseudo'], $_POST['user_r_pseudo'], $_POST['parrain_pseudo'])) {
    $userEmail = $_POST['user_email'];
    $phoneNumber = $_POST['phone_number'];
    $userLPseudo = $_POST['user_l_pseudo'];
    $userRPseudo = $_POST['user_r_pseudo'];
    $parrainPseudo = $_POST['parrain_pseudo'];

    try {
        global $pdo;
        
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM Users WHERE user_l_pseudo = ?');
        $stmt->execute([$userLPseudo]);
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => translate('invitation_code_left_exists')]);
            exit;
        }

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM Users WHERE user_r_pseudo = ?');
        $stmt->execute([$userRPseudo]);
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => translate('invitation_code_right_exists')]);
            exit;
        }

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM Users WHERE (user_r_pseudo = ? OR user_l_pseudo = ?) AND (user_r_pseudo = ? OR user_l_pseudo = ?)');
        $stmt->execute([$parrainPseudo, $parrainPseudo, $parrainPseudo, $parrainPseudo]);
        if ($stmt->fetchColumn() == 0) {
            echo json_encode(['success' => false, 'message' => translate('invalid_invitation_code')]);
            exit;
        }

        echo json_encode(['success' => true, 'message' => translate('validation_passed')]);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => translate('database_error') . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => translate('invalid_data')]);
}
?>