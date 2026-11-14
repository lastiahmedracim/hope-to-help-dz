<?php
session_start();
require_once('adb.php');
require_once('ahash.php');

$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = filter_input(INPUT_POST, 'otp', FILTER_SANITIZE_STRING);
    $newPassword = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);
    $confirmPassword = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);
    $admin_email = $_SESSION['reset_email'] ?? '';

    if (empty($otp) || empty($newPassword) || empty($confirmPassword)) {
        $errorMessage = 'Veuillez remplir tous les champs.';
    } elseif ($newPassword !== $confirmPassword) {
        $errorMessage = 'Les mots de passe ne correspondent pas.';
    } elseif (strlen($newPassword) < 10 || !preg_match('/[A-Z]/', $newPassword) || !preg_match('/[0-9]/', $newPassword)) {
        $errorMessage = 'Le mot de passe doit contenir au moins 10 caractères, une majuscule et un chiffre.';
    } else {
        try {
            $stmt = $conn->prepare("SELECT admin_id FROM Admins WHERE admin_email = :email AND OTP = :otp");
            $stmt->bindParam(':email', $admin_email);
            $stmt->bindParam(':otp', $otp);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                $errorMessage = 'Code OTP invalide.';
            } else {
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);

                $salt = generateSalt();
                $pepper = generatePepper();

                $hashedPasswordArray = hashPassword($newPassword, $pepper);
                $hashedPassword = $hashedPasswordArray['hash'];
                $salt = $hashedPasswordArray['salt'];

                $stmt = $conn->prepare("UPDATE Admins SET admin_password = :password, salt = :salt, pepper = :pepper, OTP = NULL WHERE admin_id = :admin_id");
                $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                $stmt->bindParam(':salt', $salt, PDO::PARAM_STR);
                $stmt->bindParam(':pepper', $pepper, PDO::PARAM_STR);
                $stmt->bindParam(':admin_id', $admin['admin_id'], PDO::PARAM_INT);
                
                if ($stmt->execute()) {
                    $successMessage = 'Mot de passe mis à jour avec succès.';
                    $_SESSION['password_reset'] = true;
                    header('Location: index.php');
                    exit();
                } else {
                    $errorMessage = 'Erreur lors de la mise à jour du mot de passe. Veuillez réessayer.';
                }
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $errorMessage = 'Erreur de la base de données. Veuillez réessayer.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe</title>
    <link rel='stylesheet' type='text/css' media='screen' href='../..\Css/form.css'>
</head>
<body>
    <section id="reset-password">
        <div class="container">
            <h1>Réinitialiser le mot de passe</h1>
            <?php if (!empty($errorMessage)): ?>
                <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
            <?php if (!empty($successMessage)): ?>
                <div class="success-message"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>
            <form method="POST" action="adp_r.php">
                <div class="row">
                    <div class="input-group">
                        <label for="otp">Code OTP :</label>
                        <input type="text" id="otp" name="otp" placeholder="Entrez le code OTP reçu par email" required>
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">
                        <label for="new_password">Nouveau mot de passe :</label>
                        <input type="password" id="new_password" name="new_password" placeholder="Entrez votre nouveau mot de passe" required>
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">
                        <label for="confirm_password">Confirmez le mot de passe :</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmez votre mot de passe" required>
                    </div>
                </div>
                <div id="ad-messages"></div>
                <input type="submit" value="Réinitialiser le mot de passe">
            </form>
        </div>
    </section>
    <script src="./adp_r.js"></script>
</body>
</html>
