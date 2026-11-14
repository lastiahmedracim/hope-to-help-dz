<?php
require_once('../functions/db.php');

header('Content-Type: application/json');

function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$translations = [
    'fr' => [
        'all_fields_required' => 'Tous les champs sont obligatoires.',
        'invalid_email' => 'Adresse email invalide.',
        'invalid_phone' => 'Numéro de téléphone invalide.',
        'invalid_address' => 'L\'adresse n\'est pas valide. Elle doit contenir au moins une lettre et un chiffre, et peut inclure des espaces et les caractères ,.-',
        'already_submitted' => 'Vous avez déjà soumis vos informations pour ce projet.',
        'submission_success' => 'Vos informations ont été soumises avec succès. Merci pour votre intérêt dans ce projet!',
        'submission_failure' => 'Échec de la soumission des informations. Veuillez réessayer plus tard.',
        'database_error' => 'Une erreur de base de données s\'est produite. Veuillez réessayer plus tard.',
        'invalid_request' => 'Méthode de requête invalide.'
    ],
    'ar' => [
        'all_fields_required' => 'جميع الحقول مطلوبة.',
        'invalid_email' => 'عنوان البريد الإلكتروني غير صالح.',
        'invalid_phone' => 'رقم الهاتف غير صالح.',
        'invalid_address' => 'العنوان غير صالح. يجب أن يحتوي على الأقل على حرف واحد ورقم واحد، ويمكن أن يتضمن مسافات والأحرف ,.-',
        'already_submitted' => 'لقد قمت بالفعل بتقديم معلوماتك لهذا المشروع.',
        'submission_success' => 'تم تقديم معلوماتك بنجاح. شكرا لاهتمامك بهذا المشروع!',
        'submission_failure' => 'فشل في تقديم المعلومات. يرجى المحاولة مرة أخرى لاحقًا.',
        'database_error' => 'حدث خطأ في قاعدة البيانات. يرجى المحاولة مرة أخرى لاحقًا.',
        'invalid_request' => 'طريقة الطلب غير صالحة.'
    ],
    'en' => [
        'all_fields_required' => 'All fields are required.',
        'invalid_email' => 'Invalid email address.',
        'invalid_phone' => 'Invalid phone number.',
        'invalid_address' => 'The address is not valid. It must contain at least one letter and one number, and may include spaces and the characters ,.-',
        'already_submitted' => 'You have already submitted your information for this project.',
        'submission_success' => 'Your information has been submitted successfully. Thank you for your interest in this project!',
        'submission_failure' => 'Failed to submit information. Please try again later.',
        'database_error' => 'A database error occurred. Please try again later.',
        'invalid_request' => 'Invalid request method.'
    ]
];

function translate($key, $lang) {
    global $translations;
    return $translations[$lang][$key] ?? $translations['fr'][$key];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = isset($_POST['project_id']) ? validate_input($_POST['project_id']) : '';
    $first_name = isset($_POST['first_name']) ? validate_input($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? validate_input($_POST['last_name']) : '';
    $phone_number = isset($_POST['phone_number']) ? validate_input($_POST['phone_number']) : '';
    $email = isset($_POST['email']) ? validate_input($_POST['email']) : '';
    $personal_address = isset($_POST['personal_address']) ? validate_input($_POST['personal_address']) : '';
    $lang = isset($_POST['lang']) ? validate_input($_POST['lang']) : 'fr';

    if (empty($project_id) || empty($first_name) || empty($last_name) || empty($phone_number) || empty($email) || empty($personal_address)) {
        echo json_encode(['status' => 'error', 'message' => translate('all_fields_required', $lang)]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => translate('invalid_email', $lang)]);
        exit;
    }

    if (!preg_match("/^[0-9]{10}$/", $phone_number)) {
        echo json_encode(['status' => 'error', 'message' => translate('invalid_phone', $lang)]);
        exit;
    }

    if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z0-9\s,.-]+$/', $personal_address)) {
        echo json_encode(['status' => 'error', 'message' => translate('invalid_address', $lang)]);
        exit;
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM Partners WHERE email = ? AND project_id = ?");
        $stmt->execute([$email, $project_id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => translate('already_submitted', $lang)]);
            exit;
        }

        session_start();
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        $stmt = $pdo->prepare("INSERT INTO Partners (first_name, last_name, phone_number, email, personal_address, project_id, user_id, accp) VALUES (?, ?, ?, ?, ?, ?, ?, FALSE)");
        
        if ($stmt->execute([$first_name, $last_name, $phone_number, $email, $personal_address, $project_id, $user_id])) {
            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => translate('submission_success', $lang)]);
        } else {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => translate('submission_failure', $lang)]);
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => translate('database_error', $lang)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => translate('invalid_request', $lang)]);
}