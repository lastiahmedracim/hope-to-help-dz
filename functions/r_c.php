<?php
session_start();
require_once('db.php');

function getUserLanguage() {
    return $_COOKIE['language'] ?? 'fr';
}

$translations = [
    'fr' => [
        'send_info' => '<p>Envoyez vos informations personnelles actuelles avec pièce d\'identité à cette adresse email : <a href="mailto:help.dz2024@gmail.com" style="color: #ccc;">help.dz2024@gmail.com</a></p>',
        'invalid_request' => 'Requête invalide.',
        'database_error' => 'Erreur de base de données : '
    ],
    'en' => [
        'send_info' => '<p>Send your current personal information with ID to this email address: <a href="mailto:help.dz2024@gmail.com" style="color: #ccc;">help.dz2024@gmail.com</a></p>',
        'invalid_request' => 'Invalid request.',
        'database_error' => 'Database error: '
    ],
    'ar' => [
        'send_info' => '<p>أرسل معلوماتك الشخصية الحالية مع إثبات الهوية إلى عنوان البريد الإلكتروني هذا: <a href="mailto:help.dz2024@gmail.com" style="color: #ccc;">help.dz2024@gmail.com</a></p>',
        'invalid_request' => 'طلب غير صالح.',
        'database_error' => 'خطأ في قاعدة البيانات: '
    ]
];

function translate($key) {
    global $translations;
    $lang = getUserLanguage();
    return $translations[$lang][$key] ?? $translations['en'][$key];
}

function sendJsonResponse($success, $message = '') {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['course_id']) || !isset($_SESSION['user_r_pseudo'])) {
    sendJsonResponse(false, translate('invalid_request'));
}

$course_id = filter_var($data['course_id'], FILTER_VALIDATE_INT);
$user_r_pseudo = $_SESSION['user_r_pseudo'];

if ($course_id === false) {
    sendJsonResponse(false, translate('invalid_request'));
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            u.user_id,
            c.contact_id,
            CASE WHEN cert.certification_id IS NOT NULL THEN 1 ELSE 0 END AS certification_exists
        FROM Users u
        JOIN Courses c ON c.course_id = :course_id
        LEFT JOIN Certifications cert ON cert.user_id = u.user_id AND cert.course_id = :course_id
        WHERE u.user_r_pseudo = :user_r_pseudo
    ");
    $stmt->execute(['course_id' => $course_id, 'user_r_pseudo' => $user_r_pseudo]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        sendJsonResponse(false, translate('invalid_request'));
    }

    if ($result['certification_exists']) {
        sendJsonResponse(true, translate('send_info'));
    }

    $insertStmt = $pdo->prepare("INSERT INTO Certifications (user_id, course_id, contact_id) VALUES (:user_id, :course_id, :contact_id)");
    $insertStmt->execute([
        'user_id' => $result['user_id'],
        'course_id' => $course_id,
        'contact_id' => $result['contact_id']
    ]);

    sendJsonResponse(true, translate('send_info'));

} catch (PDOException $e) {
    error_log('Database error in r_c.php: ' . $e->getMessage());
    sendJsonResponse(false, translate('database_error') . $e->getMessage());
}
?>