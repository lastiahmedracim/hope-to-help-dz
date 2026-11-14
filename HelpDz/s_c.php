<?php
require_once('../functions/db.php');

function verifyRecaptcha($token) {
    $secret = '6Ld2gA8qAAAAAH-V5DKHAuE4BEVP5eLQM2ssQVTJ';
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$token);
    $responseData = json_decode($verifyResponse);
    return $responseData->success && $responseData->score >= 0.5;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];
    $recaptcha_token = $_POST["recaptcha_token"];

    if (!verifyRecaptcha($recaptcha_token)) {
        echo 'error';
        exit;
    }

    try {
        $stmt = $GLOBALS['pdo']->prepare("INSERT INTO ContactSubmissions (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $subject, $message]);

        echo 'success';
    } catch (PDOException $e) {
        echo 'error';
    }
}
?>