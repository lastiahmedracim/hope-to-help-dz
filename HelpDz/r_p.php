<?php
require_once('../functions/db.php');
require_once('../functions/hash.php');

$translations = [
    'fr' => [
        'invalid_email' => "Adresse e-mail invalide",
        'invalid_pseudo' => "Pseudonyme droit invalide",
        'invalid_otp' => "Code OTP invalide",
        'invalid_password' => "Mot de passe invalide",
        'db_error' => "Erreur de connexion à la base de données",
        'invalid_credentials' => "E-mail, pseudonyme droit ou code OTP invalide",
        'reset_success' => "Réinitialisation du mot de passe réussie"
    ],
    'ar' => [
        'invalid_email' => "عنوان البريد الإلكتروني غير صالح",
        'invalid_pseudo' => "الاسم المستعار الأيمن غير صالح",
        'invalid_otp' => "رمز OTP غير صالح",
        'invalid_password' => "كلمة المرور غير صالحة",
        'db_error' => "خطأ في الاتصال بقاعدة البيانات",
        'invalid_credentials' => "البريد الإلكتروني أو الاسم المستعار الأيمن أو رمز OTP غير صالح",
        'reset_success' => "تم إعادة تعيين كلمة المرور بنجاح"
    ],
    'en' => [
        'invalid_email' => "Invalid email address",
        'invalid_pseudo' => "Invalid right pseudonym",
        'invalid_otp' => "Invalid OTP code",
        'invalid_password' => "Invalid password",
        'db_error' => "Database connection error",
        'invalid_credentials' => "Invalid email, right pseudonym, or OTP code",
        'reset_success' => "Password reset successful"
    ]
];

$lang = isset($_COOKIE['language']) ? $_COOKIE['language'] : 'fr';

function t($key) {
    global $translations, $lang;
    return $translations[$lang][$key] ?? $translations['fr'][$key];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_EMAIL);
    $user_r_pseudo = filter_input(INPUT_POST, 'user_r_pseudo', FILTER_SANITIZE_STRING);
    $otp = filter_input(INPUT_POST, 'otp', FILTER_SANITIZE_STRING);
    $new_password = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);

    $errors = [];

    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = t('invalid_email');
    }

    if (empty($user_r_pseudo)) {
        $errors[] = t('invalid_pseudo');
    }

    if (strlen($otp) !== 6 || !ctype_digit($otp)) {
        $errors[] = t('invalid_otp');
    }

    if (strlen($new_password) < 10 || !preg_match('/[A-Z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
        $errors[] = t('invalid_password');
    }

    if (!empty($errors)) {
        echo json_encode(['status' => 'error', 'messages' => $errors]);
        exit;
    }

    $pdo = $GLOBALS['pdo'];

    if (!$pdo) {
        echo json_encode(['status' => 'error', 'messages' => [t('db_error')]]);
        exit;
    }

    $stmt = $pdo->prepare('SELECT user_id FROM Users WHERE user_email = ? AND user_r_pseudo = ? AND OTP = ?');
    $stmt->execute([$user_email, $user_r_pseudo, $otp]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['status' => 'error', 'messages' => [t('invalid_credentials')]]);
        exit;
    }

    $salt = generateSalt();
    $pepper = generatePepper();

    $hashedPasswordArray = hashPassword($new_password, $salt, $pepper);
    $hashedPassword = $hashedPasswordArray['hash'];
    $salt = $hashedPasswordArray['salt'];
    $pepper = $hashedPasswordArray['pepper'];

    $stmt = $pdo->prepare('UPDATE Users SET user_password = ?, salt = ?, pepper = ?, OTP = NULL WHERE user_id = ?');
    $stmt->execute([$hashedPassword, $salt, $pepper, $user['user_id']]);

    echo json_encode(['status' => 'success', 'message' => t('reset_success')]);
    exit;
}
?>