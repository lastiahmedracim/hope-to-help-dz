<?php
require_once('../functions/db.php');
require_once('../functions/hash.php');

$translations = [
    'fr' => [
        'error_occurred' => "Une erreur s'est produite",
        'invalid_user_id' => "ID utilisateur invalide",
        'weak_password' => "Le mot de passe est trop faible",
        'db_error' => "Erreur de connexion à la base de données",
        'reset_error' => "Erreur lors de la réinitialisation du mot de passe",
        'invalid_request' => "Méthode de requête invalide"
    ],
    'ar' => [
        'error_occurred' => "حدث خطأ",
        'invalid_user_id' => "معرف المستخدم غير صالح",
        'weak_password' => "كلمة المرور ضعيفة جدًا",
        'db_error' => "خطأ في الاتصال بقاعدة البيانات",
        'reset_error' => "خطأ في إعادة تعيين كلمة المرور",
        'invalid_request' => "طريقة طلب غير صالحة"
    ],
    'en' => [
        'error_occurred' => "An error occurred",
        'invalid_user_id' => "Invalid user ID",
        'weak_password' => "The password is too weak",
        'db_error' => "Database connection error",
        'reset_error' => "Error resetting password",
        'invalid_request' => "Invalid request method"
    ]
];

$lang = isset($_COOKIE['language']) ? $_COOKIE['language'] : 'fr';

function t($key) {
    global $translations, $lang;
    return $translations[$lang][$key] ?? $translations['fr'][$key];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    $new_password = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);

    if (!filter_var($user_id, FILTER_VALIDATE_INT) || $user_id <= 0) {
        error_log(t('invalid_user_id'));
        echo json_encode(["success" => false, "message" => t('error_occurred')]);
        exit;
    }

    if (strlen($new_password) < 10 || !preg_match('/[A-Z]/', $new_password) || !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $new_password) || !preg_match('/\d/', $new_password)) {
        error_log(t('weak_password'));
        echo json_encode(["success" => false, "message" => t('error_occurred')]);
        exit;
    }

    $pdo = $GLOBALS['pdo'];

    if (!$pdo) {
        error_log(t('db_error'));
        echo json_encode(["success" => false, "message" => t('error_occurred')]);
        exit;
    }

    $salt = generateSalt();
    $pepper = generatePepper();
    $hashedPasswordArray = hashPassword($new_password, $salt, $pepper);
    $hashedPassword = $hashedPasswordArray['hash'];
    $salt = $hashedPasswordArray['salt'];
    $pepper = $hashedPasswordArray['pepper'];

    $stmt = $pdo->prepare('UPDATE Users SET user_password = ?, salt = ?, pepper = ? WHERE user_id = ?');
    $success = $stmt->execute([$hashedPassword, $salt, $pepper, $user_id]);

    if ($success) {
        session_start();
        session_regenerate_id(true);
        $_SESSION['user_email'] = getUserEmailById($pdo, $user_id);
        header('Location: ../index.php');
        exit;
    } else {
        error_log(t('reset_error'));
        echo json_encode(["success" => false, "message" => t('error_occurred')]);
    }

} else {
    error_log(t('invalid_request'));
    echo json_encode(["success" => false, "message" => t('error_occurred')]);
}

function getUserEmailById($pdo, $user_id) {
    $stmt = $pdo->prepare('SELECT user_email FROM Users WHERE user_id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user['user_email'];
}
?>