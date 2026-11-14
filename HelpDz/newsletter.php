<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once('../functions/db.php');

header('Content-Type: application/json');

function translateMessage($message) {
    $translations = [
        'fr' => [
            'This email address is already subscribed.' => 'Cette adresse e-mail est déjà abonnée.',
            'You are subscribed to the newsletter.' => 'Vous êtes abonné à la newsletter.',
            'Error subscribing to the newsletter.' => 'Erreur lors de l\'abonnement à la newsletter.',
            'Invalid request method.' => 'Méthode de requête invalide.',
            'Database connection error.' => 'Erreur de connexion à la base de données.'
        ],
        'ar' => [
            'This email address is already subscribed.' => 'عنوان البريد الإلكتروني هذا مشترك بالفعل.',
            'You are subscribed to the newsletter.' => 'أنت مشترك في النشرة الإخبارية.',
            'Error subscribing to the newsletter.' => 'حدث خطأ أثناء الاشتراك في النشرة الإخبارية.',
            'Invalid request method.' => 'طريقة الطلب غير صالحة.',
            'Database connection error.' => 'خطأ في الاتصال بقاعدة البيانات.'
        ],
        'en' => [
            'This email address is already subscribed.' => 'This email address is already subscribed.',
            'You are subscribed to the newsletter.' => 'You are subscribed to the newsletter.',
            'Error subscribing to the newsletter.' => 'Error subscribing to the newsletter.',
            'Invalid request method.' => 'Invalid request method.',
            'Database connection error.' => 'Database connection error.'
        ]
    ];

    $lang = $_COOKIE['language'] ?? 'fr';
    return $translations[$lang][$message] ?? $message;
}

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nw_email'])) {
        $email = $_POST['nw_email'];

        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM Subscribers WHERE sub_email = :email");
        $stmt_check->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt_check->execute();
        $count = $stmt_check->fetchColumn();

        if ($count > 0) {
            $response = array('success' => false, 'message' => translateMessage('This email address is already subscribed.'));
            echo json_encode($response);
        } else {
            $stmt_insert = $pdo->prepare("INSERT INTO Subscribers (sub_email, subscribed_at) VALUES (:email, NOW())");
            $stmt_insert->bindParam(':email', $email, PDO::PARAM_STR);

            if ($stmt_insert->execute()) {
                $response = array('success' => true, 'message' => translateMessage('You are subscribed to the newsletter.'));
                echo json_encode($response);
            } else {
                $response = array('success' => false, 'message' => translateMessage('Error subscribing to the newsletter.'));
                echo json_encode($response);
            }
        }
    } else {
        $response = array('success' => false, 'message' => translateMessage('Invalid request method.'));
        echo json_encode($response);
    }
} catch (PDOException $e) {
    $response = array('success' => false, 'message' => translateMessage('Database connection error.'));
    echo json_encode($response);
}
?>
