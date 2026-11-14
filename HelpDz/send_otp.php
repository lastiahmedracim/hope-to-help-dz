<?php
require_once('../vendor/autoload.php');
require_once('../functions/db.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$translations = [
    'fr' => [
        'invalid_email' => "Adresse e-mail invalide",
        'invalid_pseudo' => "Pseudonyme droit invalide",
        'db_error' => "Erreur de connexion à la base de données",
        'user_not_found' => "Aucun utilisateur trouvé avec cette combinaison d'email et de pseudonyme droit",
        'email_subject' => "Réinitialisation de votre mot de passe",
        'email_body' => "Votre code de réinitialisation est : ",
        'email_sent' => "Un code de réinitialisation a été envoyé à votre adresse email",
        'email_error' => "Erreur lors de l'envoi de l'email. Veuillez réessayer. Erreur : "
    ],
    'ar' => [
        'invalid_email' => "عنوان البريد الإلكتروني غير صالح",
        'invalid_pseudo' => "الاسم المستعار الأيمن غير صالح",
        'db_error' => "خطأ في الاتصال بقاعدة البيانات",
        'user_not_found' => "لم يتم العثور على مستخدم بهذه المجموعة من البريد الإلكتروني والاسم المستعار الأيمن",
        'email_subject' => "إعادة تعيين كلمة المرور الخاصة بك",
        'email_body' => "رمز إعادة التعيين الخاص بك هو: ",
        'email_sent' => "تم إرسال رمز إعادة التعيين إلى عنوان بريدك الإلكتروني",
        'email_error' => "خطأ في إرسال البريد الإلكتروني. يرجى المحاولة مرة أخرى. خطأ: "
    ],
    'en' => [
        'invalid_email' => "Invalid email address",
        'invalid_pseudo' => "Invalid right pseudonym",
        'db_error' => "Database connection error",
        'user_not_found' => "No user found with this email and right pseudonym combination",
        'email_subject' => "Reset your password",
        'email_body' => "Your reset code is: ",
        'email_sent' => "A reset code has been sent to your email address",
        'email_error' => "Error sending email. Please try again. Error: "
    ]
];

function t($key, $lang) {
    global $translations;
    return $translations[$lang][$key] ?? $translations['en'][$key];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_EMAIL);
    $user_r_pseudo = filter_input(INPUT_POST, 'user_r_pseudo', FILTER_SANITIZE_STRING);
    $lang = filter_input(INPUT_POST, 'lang', FILTER_SANITIZE_STRING) ?? 'fr';

    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => t('invalid_email', $lang)]);
        exit;
    }

    if (empty($user_r_pseudo)) {
        echo json_encode(["success" => false, "message" => t('invalid_pseudo', $lang)]);
        exit;
    }

    $pdo = $GLOBALS['pdo'];

    if (!$pdo) {
        echo json_encode(["success" => false, "message" => t('db_error', $lang)]);
        exit;
    }

    $stmt = $pdo->prepare('SELECT user_id FROM Users WHERE user_email = ? AND user_r_pseudo = ?');
    $stmt->execute([$user_email, $user_r_pseudo]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["success" => false, "message" => t('user_not_found', $lang)]);
        exit;
    }

    $otp = sprintf("%06d", mt_rand(0, 999999));

    $stmt = $pdo->prepare('UPDATE Users SET OTP = ? WHERE user_id = ?');
    $stmt->execute([$otp, $user['user_id']]);

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.hopetohelp.dz';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'support@hopetohelp.dz';
        $mail->Password   = '(#c:TUSLA^j7';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('support@hopetohelp.dz', 'HelpDz');
        $mail->addAddress($user_email);

        $mail->isHTML(true);
        $mail->Subject = t('email_subject', $lang);
        $mail->Body    = t('email_body', $lang) . $otp;

        $mail->send();
        echo json_encode(["success" => true, "message" => t('email_sent', $lang)]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => t('email_error', $lang) . $mail->ErrorInfo]);
    }
    exit;
}
?>