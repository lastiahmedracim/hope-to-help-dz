<?php
session_start();

if (isset($_SESSION['admin_email'])) {
    header('Location: 1s2qZa-lf.php');
    exit();
}

require_once('adb.php');
require_once('ahash.php');

header('Content-Type: application/json');

function verifyRecaptcha($token) {
    $secret = '6Ld2gA8qAAAAAH-V5DKHAuE4BEVP5eLQM2ssQVTJ';
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$token);
    $responseData = json_decode($verifyResponse);
    return $responseData->success && $responseData->score >= 0.5;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant_admin = $_POST['email_or_phone'];
    $mot_de_passe_admin = $_POST['password'];
    $recaptcha_token = $_POST['recaptcha_token'] ?? null;

    if (!$recaptcha_token || !verifyRecaptcha($recaptcha_token)) {
        echo json_encode(["success" => false, "message" => "Échec de la vérification reCAPTCHA. Veuillez réessayer."]);
        exit;
    }

    $conn = $GLOBALS['conn'];
    if (!$conn) {
        echo json_encode(["success" => false, "message" => "Erreur : Connexion à la base de données non disponible !"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT admin_level, admin_password, salt, pepper FROM Admins WHERE admin_email = :email OR phone_number = :phone");
    $stmt->bindParam(':email', $identifiant_admin, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $identifiant_admin, PDO::PARAM_STR);
    $stmt->execute();
    $donneesAdmin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($donneesAdmin) {
        $motDePasseHacheBDD = $donneesAdmin['admin_password'];
        $sel = $donneesAdmin['salt'];
        $poivre = $donneesAdmin['pepper'];

        if (verifyPassword($mot_de_passe_admin, $motDePasseHacheBDD, $sel, $poivre)) {
            $_SESSION['admin_email'] = $identifiant_admin;
            $_SESSION['admin_level'] = $donneesAdmin['admin_level'];
            echo json_encode(["success" => true, "message" => "Connexion réussie !"]);
        } else {
            echo json_encode(["success" => false, "message" => "Identifiants invalides. Veuillez réessayer."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Utilisateur non trouvé."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Méthode de requête invalide."]);
}
?>