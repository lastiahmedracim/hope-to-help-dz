<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_email'])) {
    header('Location: index.php');
    exit;
}

require_once('adb.php');

if (isset($conn)) {
    try {
        $admin_email = $_SESSION['admin_email'];
        $stmt = $conn->prepare("SELECT admin_level FROM Admins WHERE admin_email = :admin_email");
        $stmt->bindParam(':admin_email', $admin_email);
        $stmt->execute();
        $admin_level = $stmt->fetchColumn();

        if ($admin_level === 'main_admin') {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (!isset($_POST['admin_email'])) {
                    echo json_encode(array("success" => false, "message" => "Adresse e-mail de l'administrateur non fournie !"));
                    exit;
                }

                $admin_email = $_POST['admin_email'];
                $admin_level = $_POST['admin_level'];

                try {
                    $stmt = $conn->prepare("UPDATE Admins SET admin_level = :admin_level WHERE admin_email = :admin_email");
                    $stmt->bindParam(':admin_email', $admin_email);
                    $stmt->bindParam(':admin_level', $admin_level);
                    
                    if ($stmt->execute()) {
                        echo json_encode(array("success" => true, "message" => "Permissions mises à jour avec succès !"));
                    } else {
                        echo json_encode(array("success" => false, "message" => "Erreur lors de la mise à jour des permissions !"));
                    }
                } catch (PDOException $e) {
                    error_log("Erreur de requête à la base de données : " . $e->getMessage());
                    echo json_encode(array("success" => false, "message" => "Erreur lors de la mise à jour des permissions !"));
                }
            } else {
                echo json_encode(array("success" => false, "message" => "Requête invalide !"));
            }
        } else {
            echo json_encode(array("success" => false, "message" => "Permissions insuffisantes !"));
        }
    } catch (PDOException $e) {
        error_log("Erreur de requête à la base de données : " . $e->getMessage());
        echo json_encode(array("success" => false, "message" => "Erreur lors de la récupération du niveau d'administrateur !"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Erreur de connexion à la base de données !"));
}
?>