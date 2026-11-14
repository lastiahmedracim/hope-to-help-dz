<?php
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
    
    error_log('reCAPTCHA API Response: ' . print_r($responseData, true));
    
    return $responseData->success && $responseData->score >= 0.5;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log('Received POST request');
    
    $adminName = $_POST['admin_name'] ?? '';
    $adminLastName = $_POST['admin_last_name'] ?? '';
    $adminEmail = $_POST['admin_email'] ?? '';
    $phoneNumber = $_POST['phone_number'] ?? '';
    $adminPassword = $_POST['admin_password'] ?? '';
    $recaptcha_token = $_POST['recaptcha_token'] ?? null;

    error_log('Received data: ' . print_r($_POST, true));

    $recaptcha_token = $_POST['recaptcha_token'] ?? null;

    if (!$recaptcha_token) {
        error_log('No reCAPTCHA token provided');
        echo json_encode(["success" => false, "message" => "Token reCAPTCHA manquant."]);
        exit;
    }

    if (!verifyRecaptcha($recaptcha_token)) {
        error_log('reCAPTCHA verification failed');
        echo json_encode(["success" => false, "message" => "Échec de la vérification reCAPTCHA. Veuillez réessayer."]);
        exit;
    }

    error_log('reCAPTCHA verification passed');

    $conn = $GLOBALS['conn'];
    if (!$conn) {
        error_log('Database connection failed');
        echo json_encode(["success" => false, "message" => "Erreur : Connexion à la base de données non disponible !"]);
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM Admins WHERE admin_email = :email");
        $stmt->bindParam(':email', $adminEmail, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            error_log('Email already exists: ' . $adminEmail);
            $response = array('success' => false, 'message' => 'L\'email existe déjà. Veuillez utiliser une autre adresse email.');
            echo json_encode($response);
        } else {
            $salt = generateSalt();
            $pepper = generatePepper();

            $hashedPasswordArray = hashPassword($adminPassword, $pepper);
            $hashedPassword = $hashedPasswordArray['hash'];
            $salt = $hashedPasswordArray['salt'];

            $sql = "INSERT INTO Admins (admin_name, admin_last_name, admin_email, phone_number, admin_password, salt, pepper)
            VALUES (:name, :last_name, :email, :phone, :password, :salt, :pepper)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $adminName, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $adminLastName, PDO::PARAM_STR);
            $stmt->bindParam(':email', $adminEmail, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phoneNumber, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $stmt->bindParam(':salt', $salt, PDO::PARAM_STR);
            $stmt->bindParam(':pepper', $pepper, PDO::PARAM_STR);

            if ($stmt->execute()) {
                error_log('Admin registered successfully: ' . $adminEmail);
                session_start();
                $_SESSION['admin_email'] = $adminEmail;
                $response = array('success' => true);
                echo json_encode($response);
            } else {
                error_log('Database insertion error: ' . print_r($stmt->errorInfo(), true));
                $response = array('success' => false, 'message' => 'Erreur lors de l\'insertion dans la base de données.');
                echo json_encode($response);
            }
        }
    } catch (PDOException $e) {
        error_log('PDO Exception: ' . $e->getMessage());
        echo json_encode(["success" => false, "message" => "Erreur de base de données: " . $e->getMessage()]);
    }
} else {
    error_log('Invalid request method: ' . $_SERVER['REQUEST_METHOD']);
    echo json_encode(["success" => false, "message" => "Erreur : Méthode de requête non valide !"]);
}
?>