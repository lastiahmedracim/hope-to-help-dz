<?php
require_once('user.php');
require_once('db.php');
require_once('hash.php');

header('Content-Type: application/json');

header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self';");

$translations = [
    'fr' => [
        'recaptcha_failed' => 'La vérification reCAPTCHA a échoué. Veuillez réessayer.',
        'db_connection_error' => 'Connexion à la base de données non disponible !',
        'user_registered' => 'Utilisateur enregistré avec succès',
        'registration_error' => 'Erreur lors de l\'enregistrement de l\'utilisateur !',
        'invalid_request_method' => 'Méthode de requête invalide.'
    ],
    'en' => [
        'recaptcha_failed' => 'reCAPTCHA verification failed. Please try again.',
        'db_connection_error' => 'Database connection not available!',
        'user_registered' => 'User registered successfully',
        'registration_error' => 'Error registering user!',
        'invalid_request_method' => 'Invalid request method.'
    ],
    'ar' => [
        'recaptcha_failed' => 'فشل التحقق من رeCAPTCHA. يرجى المحاولة مرة أخرى.',
        'db_connection_error' => 'اتصال قاعدة البيانات غير متوفر!',
        'user_registered' => 'تم تسجيل المستخدم بنجاح',
        'registration_error' => 'خطأ في تسجيل المستخدم!',
        'invalid_request_method' => 'طريقة طلب غير صالحة.'
    ]
];

function verifyRecaptcha($token) {
    $secret_key = '6Ld2gA8qAAAAAH-V5DKHAuE4BEVP5eLQM2ssQVTJ';
    $verify_response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $token);
    $response_data = json_decode($verify_response);
    return $response_data->success && $response_data->score >= 0.5;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (!isset($_POST['recaptcha_token']) || !verifyRecaptcha($_POST['recaptcha_token'])) {
            throw new Exception(translate('recaptcha_failed'));
        }

        $user_name = $_POST['user_name'];
        $user_last_name = $_POST['user_last_name'];
        $user_email = $_POST['user_email'];
        $phone_number = $_POST['phone_number'];
        $user_password = $_POST['user_password'];
        $wilaya = $_POST['wilaya'];
        $city = $_POST['city'];
        $district = $_POST['district'];
        $user_address = $_POST['user_address'];
        $user_r_pseudo = $_POST['user_r_pseudo'];
        $user_l_pseudo = $_POST['user_l_pseudo'];
        $parrain_pseudo = $_POST['parrain_pseudo'];
        $rip = $_POST['rip'];

        $salt = generateSalt();
        $pepper = generatePepper();

        $hashedPasswordArray = hashPassword($user_password, $salt, $pepper);
        $hashedPassword = $hashedPasswordArray['hash'];
        $salt = $hashedPasswordArray['salt'];
        $pepper = $hashedPasswordArray['pepper'];

        $pdo = $GLOBALS['pdo'];

        if (!$pdo) {
            throw new Exception(translate('db_connection_error'));
        }

        $user = new User($user_name, $user_last_name, $user_email, $phone_number, $hashedPassword, $salt, $pepper, $wilaya, $city, $district, $user_address, $user_r_pseudo, $user_l_pseudo, $parrain_pseudo, $rip);
        $success = insertUserIntoTree($user, $pdo);

        if ($success) {
            session_start();
            $_SESSION['user_r_pseudo'] = $user_r_pseudo;
            echo json_encode(["success" => true, "message" => translate('user_registered')]);
        } else {
            throw new Exception(translate('registration_error'));
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => translate('invalid_request_method')]);
}
?>