<?php
session_start();
require_once('adb.php');
if (!isset($_SESSION['admin_email'])) {
    header('Location: index.php');
    exit;
}

if (isset($_GET['profit_id']) && isset($_GET['profit_type']) && isset($_GET['paid'])) {
    $profit_id = filter_var($_GET['profit_id'], FILTER_VALIDATE_INT);
    $profit_type = filter_var($_GET['profit_type'], FILTER_SANITIZE_STRING);
    $paid = filter_var($_GET['paid'], FILTER_SANITIZE_STRING);

    if ($profit_id === false || ($paid !== 'paid' && $paid !== 'not_paid') || ($profit_type !== 'direct' && $profit_type !== 'indirect')) {
        echo "Requête invalide.";
        exit;
    }

    $table = ($profit_type === 'direct') ? 'DirectProfit' : 'IndirectProfit';
    $id_column = ($profit_type === 'direct') ? 'direct_profit_id' : 'indirect_profit_id';

    $query = "UPDATE $table SET paid = :paid WHERE $id_column = :profit_id";
    try {
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':paid', $paid, PDO::PARAM_STR);
        $stmt->bindParam(':profit_id', $profit_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "succès";
        } else {
            echo "Aucune ligne mise à jour.";
        }
    } catch (PDOException $e) {
        error_log("Erreur de base de données : " . $e->getMessage());
        echo "Une erreur s'est produite. Veuillez réessayer plus tard.";
    }
} else {
    echo "Requête invalide.";
}
?>