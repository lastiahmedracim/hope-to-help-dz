<?php
require_once('adb.php');
header('Content-Type: application/json; charset=utf-8');

$projectId = $_POST['id'];
$type = $_POST['type'];
$action = $_POST['action'];

try {
    if ($type === 'projects') {
        if ($action === 'accept') {
            $stmt = $conn->prepare("UPDATE Projects SET vl = 1 WHERE project_id = :projectId");
        } else {
            $stmt = $conn->prepare("UPDATE Projects SET vl = 0 WHERE project_id = :projectId");
        }
    } else if ($type === 'draw') {
        if ($action === 'accept') {
            $stmt = $conn->prepare("UPDATE Projects SET vl = 1 WHERE project_id = :projectId");
        } else {
            $stmt = $conn->prepare("UPDATE Projects SET vl = 0 WHERE project_id = :projectId");
        }
    } else if ($type === 'all') {
        if ($action === 'accept') {
            $stmt = $conn->prepare("UPDATE Projects SET vl = 1 WHERE project_id = :projectId");
        } else {
            $stmt = $conn->prepare("UPDATE Projects SET vl = 0 WHERE project_id = :projectId");
        }
    } else {
        throw new Exception("Unknown type: $type");
    }

    if (isset($stmt)) {
        $stmt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        
        if (!$stmt->execute()) {
            $errorInfo = $stmt->errorInfo();
            throw new Exception("SQL Error: " . $errorInfo[2]);
        }
    } else {
        throw new Exception("No SQL statement created.");
    }

    $actionText = $action === 'accept' ? 'validé' : 'rejeté';
    $message = "Le projet a été $actionText avec succès.";

    echo json_encode([
        'success' => true,
        'message' => $message
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
?>