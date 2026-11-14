<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header('Location: index.php');
    exit;
}

require_once('..\../vendor/autoload.php');
require_once('adb.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email address"]);
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT admin_id FROM Admins WHERE admin_email = :email");
        $stmt->bindParam(':email', $admin_email);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            echo json_encode(["success" => false, "message" => "No admin found with this email"]);
            exit;
        }

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        $otp = sprintf("%06d", mt_rand(0, 999999));

        $stmt = $conn->prepare('UPDATE Admins SET OTP = :otp WHERE admin_id = :admin_id');
        $stmt->bindParam(':otp', $otp);
        $stmt->bindParam(':admin_id', $admin['admin_id']);
        $stmt->execute();

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = 'smtp.hopetohelp.dz';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'support@hopetohelp.dz';
        $mail->Password   = 'Xm1408028';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('support@hopetohelp.dz', 'HelpDz');
        $mail->addAddress($admin_email);

        $mail->isHTML(true);
        $mail->Subject = "Réinitialisation de votre mot de passe administrateur";
        $mail->Body    = "Votre code de réinitialisation est : " . $otp;

        $mail->send();

        session_start();
        $_SESSION['reset_email'] = $admin_email;

        echo json_encode(["success" => true, "message" => "Un code de réinitialisation a été envoyé à votre adresse email"]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Erreur lors de l'envoi de l'email. Veuillez réessayer. Erreur : {$mail->ErrorInfo}"]);
    }
    exit;
}
?>
