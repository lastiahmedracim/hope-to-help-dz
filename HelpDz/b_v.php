<?php
session_start();

if (!isset($_SESSION['user_r_pseudo'])) {
    header('Location: ../index.php');
    exit;
}

require_once('../functions/db.php');
global $pdo;

header('Content-Type: application/json');

header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self';");

function getUserLanguage() {
    return isset($_COOKIE['language']) ? htmlspecialchars($_COOKIE['language']) : 'fr';
}

$translations = [
    "fr" => [
        "Vous avez déjà ce cours en cours de visualisation." => "Vous visualisez déjà ce cours.",
        "Votre achat a été effectué avec succès. Vous pouvez maintenant accéder à votre cours." => "Votre achat a été effectué avec succès. Vous pouvez maintenant accéder à votre cours.",
        "Solde d'argent virtuel insuffisant pour acheter ce cours." => "Solde d'argent virtuel insuffisant pour acheter ce cours.",
        "Accès non autorisé." => "Accès non autorisé.",
        "Utilisateur non vérifié." => "Utilisateur non vérifié."
    ],
    "en" => [
        "Vous avez déjà ce cours en cours de visualisation." => "You are already viewing this course.",
        "Votre achat a été effectué avec succès. Vous pouvez maintenant accéder à votre cours." => "Your purchase has been successfully completed. You can now access your course.",
        "Solde d'argent virtuel insuffisant pour acheter ce cours." => "Insufficient virtual money balance to purchase this course.",
        "Accès non autorisé." => "Unauthorized access.",
        "Utilisateur non vérifié." => "User not verified."
    ],
    "ar" => [
        "Vous avez déjà ce cours en cours de visualisation." => "أنت بالفعل تشاهد هذه الدورة.",
        "Votre achat a été effectué avec succès. Vous pouvez maintenant accéder à votre cours." => "تمت عملية الشراء بنجاح. يمكنك الآن الوصول إلى دورتك.",
        "Solde d'argent virtuel insuffisant pour acheter ce cours." => "الرصيد الافتراضي غير كاف لشراء هذه الدورة.",
        "Accès non autorisé." => "الوصول غير مسموح.",
        "Utilisateur non vérifié." => "المستخدم غير مُتحقق منه."
    ]
];

function translate($message) {
    global $translations;
    $lang = getUserLanguage();
    return $translations[$lang][$message] ?? $message;
}

$response = ['status' => 'error', 'message' => translate("Accès non autorisé.")];

if (isset($_SESSION['user_r_pseudo']) && isset($_GET['course_id'])) {
    $userRPseudo = htmlspecialchars($_SESSION['user_r_pseudo']);
    $courseId = filter_var($_GET['course_id'], FILTER_SANITIZE_NUMBER_INT);

    try {
        $stmt = $pdo->prepare("SELECT user_id, virtual_money, is_v FROM Users WHERE user_r_pseudo = :user_r_pseudo");
        $stmt->bindParam(':user_r_pseudo', $userRPseudo, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user['is_v']) {
            $response['message'] = translate("Utilisateur non vérifié.");
        } else {
            $stmt = $pdo->prepare("SELECT price FROM Courses WHERE course_id = :course_id");
            $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
            $stmt->execute();
            $course = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare("SELECT order_status_id FROM Orders WHERE user_id = :user_id AND course_id = :course_id");
            $stmt->bindParam(':user_id', $user['user_id'], PDO::PARAM_INT);
            $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
            $stmt->execute();
            $existingOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $canBuyAgain = true;
            foreach ($existingOrders as $order) {
                if ($order['order_status_id'] == 2) {
                    $canBuyAgain = false;
                    break;
                }
            }

            if (!$canBuyAgain) {
                $response['message'] = translate("Vous avez déjà ce cours en cours de visualisation.");
            } elseif ($user['virtual_money'] >= $course['price']) {
                $newBalance = $user['virtual_money'] - $course['price'];

                $pdo->beginTransaction();
                $stmt = $pdo->prepare("UPDATE Users SET virtual_money = :new_balance, bought = TRUE WHERE user_r_pseudo = :user_r_pseudo");
                $stmt->bindParam(':new_balance', $newBalance, PDO::PARAM_INT);
                $stmt->bindParam(':user_r_pseudo', $userRPseudo, PDO::PARAM_STR);
                $stmt->execute();

                $expDate = date('Y-m-d', strtotime('+12 month'));
                $stmt = $pdo->prepare("INSERT INTO Orders (user_id, course_id, order_status_id, exp) VALUES (:user_id, :course_id, 2, :exp_date)");
                $stmt->bindParam(':user_id', $user['user_id'], PDO::PARAM_INT);
                $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
                $stmt->bindParam(':exp_date', $expDate, PDO::PARAM_STR);
                $stmt->execute();

                $stmt = $pdo->prepare("INSERT INTO TotalCourses (c_profit, user_id) VALUES (:course_price, :user_id)");
                $stmt->bindParam(':course_price', $course['price'], PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $user['user_id'], PDO::PARAM_INT);
                $stmt->execute();

                $pdo->commit();

                $response = [
                    'status' => 'success',
                    'message' => translate("Votre achat a été effectué avec succès. Vous pouvez maintenant accéder à votre cours.")
                ];
            } else {
                $response['message'] = translate("Solde d'argent virtuel insuffisant pour acheter ce cours.");
            }
        }
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $response['message'] = "An error occurred. Please try again later.";
        error_log($e->getMessage());
    }
}

echo json_encode($response);
?>