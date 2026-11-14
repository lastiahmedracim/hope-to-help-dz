<?php
require_once('adb.php');
header('Content-Type: text/html; charset=utf-8');

$type = $_GET['type'];

try {
    if ($type === 'all') {
        $stmt = $conn->prepare(
            "SELECT project_id, project_name 
            FROM Projects 
            WHERE display_location IN ('project', 'both')"
        );
    } else {
        $stmt = $conn->prepare(
            "SELECT DISTINCT p.project_id, p.project_name 
            FROM Projects p 
            WHERE p.display_location IN ('drow', 'both')"
        );
    }
    
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($projects, JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}