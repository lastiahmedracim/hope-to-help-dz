<?php

require_once('db.php');
require_once('hash.php');

header('Content-Type: application/json');

function verifyRecaptcha($token) {
    $secret = '6Ld2gA8qAAAAAH-V5DKHAuE4BEVP5eLQM2ssQVTJ';
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$token);
    $responseData = json_decode($verifyResponse);
    return $responseData->success && $responseData->score >= 0.5;
}

$translations = [
    'fr' => [
        'missing_fields' => "Champs obligatoires manquants",
        'recaptcha_failed' => "La vérification reCAPTCHA a échoué",
        'db_connection_error' => "Erreur : Connexion à la base de données non disponible !",
        'login_success' => "Connexion réussie !",
        'invalid_credentials' => "Code d'Invitation Droite ou mot de passe incorrect",
        'user_not_found' => "Utilisateur non trouvé.",
        'invalid_request_method' => "Méthode de requête invalide.",
        'error_occurred' => "Une erreur s'est produite. Veuillez réessayer plus tard."
    ],
    'ar' => [
        'missing_fields' => "حقول مطلوبة مفقودة",
        'recaptcha_failed' => "فشل التحقق من reCAPTCHA",
        'db_connection_error' => "خطأ: اتصال قاعدة البيانات غير متوفر!",
        'login_success' => "تم تسجيل الدخول بنجاح!",
        'invalid_credentials' => "رمز الدعوة اليمين أو كلمة المرور غير صحيحة",
        'user_not_found' => "لم يتم العثور على المستخدم.",
        'invalid_request_method' => "طريقة طلب غير صالحة.",
        'error_occurred' => "حدث خطأ. يرجى المحاولة مرة أخرى لاحقًا."
    ],
    'en' => [
        'missing_fields' => "Missing required fields",
        'recaptcha_failed' => "reCAPTCHA verification failed",
        'db_connection_error' => "Error: Database connection not available!",
        'login_success' => "Login successful!",
        'invalid_credentials' => "Invalid Right Invitation Code or password",
        'user_not_found' => "User not found.",
        'invalid_request_method' => "Invalid request method.",
        'error_occurred' => "An error occurred. Please try again later."
    ]
];

$lang = isset($_POST['lang']) ? $_POST['lang'] : 'fr';

function t($key) {
    global $translations, $lang;
    return $translations[$lang][$key] ?? $translations['fr'][$key];
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_identifier = $_POST['user_r_pseudo'] ?? null;
        $user_password = $_POST['password'] ?? null;
        $recaptcha_token = $_POST['recaptcha_token'] ?? null;

        if (!$user_identifier || !$user_password || !$recaptcha_token) {
            throw new Exception(t('missing_fields'));
        }

        if (!verifyRecaptcha($recaptcha_token)) {
            throw new Exception(t('recaptcha_failed'));
        }

        $pdo = $GLOBALS['pdo'];
        if (!$pdo) {
            throw new Exception(t('db_connection_error'));
        }

        $sql = "SELECT user_r_pseudo, user_password, salt, pepper FROM Users WHERE user_r_pseudo = :user_identifier";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_identifier', $user_identifier, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $hashedPasswordFromDB = $row['user_password'];
            $saltFromDB = $row['salt'];
            $pepperFromDB = $row['pepper'];

            if (verifyPassword($user_password, $hashedPasswordFromDB, $saltFromDB, $pepperFromDB)) {
                session_start();
                $_SESSION['user_r_pseudo'] = $row['user_r_pseudo'];
                echo json_encode(["success" => true, "message" => t('login_success')]);
            } else {
                echo json_encode(["success" => false, "message" => t('invalid_credentials')]);
            }
        } else {
            echo json_encode(["success" => false, "message" => t('user_not_found')]);
        }
    } else {
        echo json_encode(["success" => false, "message" => t('invalid_request_method')]);
    }
} catch (Exception $e) {
    error_log('Login error: ' . $e->getMessage());
    echo json_encode(["success" => false, "message" => t('error_occurred')]);
}
?>