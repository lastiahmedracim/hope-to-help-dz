<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header('Location: index.php');
    exit;
}
require_once('adb.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['v_i_id'])) {
    $v_i_id = $_POST['v_i_id'];

    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("SELECT * FROM ValidatedInfo WHERE v_i_id = :v_i_id");
        $stmt->bindParam(':v_i_id', $v_i_id);
        $stmt->execute();
        $validatedInfo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($validatedInfo) {
            $updateStmt = $conn->prepare("UPDATE Users SET 
                user_name = :user_name,
                user_last_name = :user_last_name,
                wilaya = :wilaya,
                city = :city,
                district = :district,
                user_address = :user_address,
                phone_number = :phone_number
                WHERE user_id = :user_id");

            $updateStmt->execute([
                ':user_name' => $validatedInfo['user_name'],
                ':user_last_name' => $validatedInfo['user_last_name'],
                ':wilaya' => $validatedInfo['wilaya'],
                ':city' => $validatedInfo['city'],
                ':district' => $validatedInfo['district'],
                ':user_address' => $validatedInfo['user_address'],
                ':phone_number' => $validatedInfo['phone_number'],
                ':user_id' => $validatedInfo['user_id']
            ]);

            $statusStmt = $conn->prepare("UPDATE ValidatedInfo SET v_i_s = 1 WHERE v_i_id = :v_i_id");
            $statusStmt->bindParam(':v_i_id', $v_i_id);
            $statusStmt->execute();

            $conn->commit();

            echo json_encode(['success' => true, 'message' => 'Changements validés avec succès']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Informations validées non trouvées']);
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        error_log("Erreur de base de données : " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Une erreur est survenue lors de la validation des changements']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
}
?>
