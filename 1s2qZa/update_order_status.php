<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header('Location: index.php');
    exit;
}

require_once('adb.php');

if ($admin_level === 'main_admin') {

if (isset($_GET['order_id']) && isset($_GET['order_status']) && isset($_GET['exp_date'])) {
    $orderId = $_GET['order_id'];
    $newStatus = $_GET['order_status'];
    $expDate = $_GET['exp_date'];

    try {
        $stmt = $conn->prepare("UPDATE Commandes SET statut_commande_id = (SELECT statut_commande_id FROM StatutCommande WHERE description_statut_commande = ?), exp = ? WHERE id_commande = ?");
        $stmt->execute([$newStatus, $expDate, $orderId]);

        if ($stmt->rowCount() > 0) {
            echo 'succès';
        } else {
            echo 'Échec de la mise à jour du statut de la commande ou de la date d\'expiration';
        }
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo 'Requête invalide';
}

}
?>
