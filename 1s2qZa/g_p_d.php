<?php
require_once('adb.php');
header('Content-Type: application/json; charset=utf-8');

$projectId = $_GET['id'];
$type = $_GET['type'];

try {
    if ($type === 'all') {
        $stmt = $conn->prepare("
            SELECT p.*, u.user_name, u.user_last_name, u.user_email, u.phone_number
            FROM Projects p
            LEFT JOIN Users u ON p.user_id = u.user_id
            WHERE p.project_id = :projectId
        ");
        
        $stmt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        $stmt->execute();
        $projectDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmtPartners = $conn->prepare("
            SELECT *
            FROM Partners
            WHERE project_id = :projectId
        ");
        $stmtPartners->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        $stmtPartners->execute();
        $partners = $stmtPartners->fetchAll(PDO::FETCH_ASSOC);

        $projectDetails['partners'] = $partners;
        
    } else {
        $stmt = $conn->prepare("
            SELECT p.*, u.user_name, u.user_last_name, u.user_email, u.phone_number
            FROM Projects p
            LEFT JOIN Users u ON p.user_id = u.user_id
            WHERE p.project_id = :projectId
        ");
        
        $stmt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        $stmt->execute();
        $projectDetails = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($projectDetails) {
        array_walk_recursive($projectDetails, function(&$item) {
            $item = $item === null ? '' : $item;
        });
        
        echo json_encode($projectDetails, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['error' => 'Project not found']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>