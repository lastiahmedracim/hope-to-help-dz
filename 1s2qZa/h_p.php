<?php
ob_start();

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

ini_set('error_log', __DIR__ . '/php_errors.log');

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("PHP Error [$errno]: $errstr in $errfile on line $errline");
    return true;
});

function debug($message) {
    error_log(date('[Y-m-d H:i:s] ') . "DEBUG: " . $message);
}

function jsonResponse($success, $message, $data = null) {
    if (ob_get_length()) ob_clean();
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

try {
    debug("Script started");
    
    session_start();
    debug("Session started");
    
    $requiredFiles = ['adb.php', 'ch_ad_l.php'];
    foreach ($requiredFiles as $file) {
        if (!file_exists($file)) {
            throw new Exception("Required file $file not found");
        }
        require_once($file);
    }
    debug("Required files included");

    if (!isset($conn) || !($conn instanceof PDO)) {
        throw new Exception("Database connection failed: PDO connection not established");
    }
    debug("Database connection successful");

    if (!isset($_SESSION['admin_email'])) {
        debug("Admin email not set in session");
        jsonResponse(false, 'Non autorisé');
    }

    $adminEmail = $_SESSION['admin_email'];
    $adminLevel = getAdminLevel($adminEmail, $conn);
    if ($adminLevel !== 'main_admin' && $adminLevel !== 'sub_admin') {
        debug("Invalid admin level: $adminLevel");
        jsonResponse(false, 'Non autorisé');
    }
    debug("Admin authentication successful for $adminEmail");

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        debug("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
        jsonResponse(false, 'Méthode non autorisée');
    }
    debug("POST request received");

    $partnerId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    debug("Received input - partnerId: $partnerId, action: $action");

    if ($partnerId === false || $partnerId === null) {
        debug("Invalid partner ID");
        jsonResponse(false, 'ID de partenaire invalide');
    }

    if ($action !== 'accept' && $action !== 'refuse') {
        debug("Invalid action: $action");
        jsonResponse(false, 'Action non valide');
    }

    $newStatus = ($action === 'accept') ? 1 : 0;
    debug("Preparing SQL statement");
    $stmt = $conn->prepare("UPDATE Partners SET accp = :status WHERE partner_id = :id");
    if (!$stmt) {
        throw new Exception("SQL prepare error: " . $conn->errorInfo()[2]);
    }
    
    debug("Binding parameters");
    $stmt->bindParam(':status', $newStatus, PDO::PARAM_INT);
    $stmt->bindParam(':id', $partnerId, PDO::PARAM_INT);
    
    debug("Executing statement");
    $result = $stmt->execute();

    if ($result) {
        debug("Partner status updated successfully");
        debug("Preparing statement to get project ID");
        $stmt = $conn->prepare("SELECT project_id FROM Partners WHERE partner_id = :id");
        if (!$stmt) {
            throw new Exception("SQL prepare error: " . $conn->errorInfo()[2]);
        }
        
        debug("Binding parameter for project ID query");
        $stmt->bindParam(':id', $partnerId, PDO::PARAM_INT);
        
        debug("Executing project ID query");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            debug("No partner found with ID: $partnerId");
            jsonResponse(false, "Aucun partenaire trouvé avec l'ID fourni");
        }
        
        $projectId = $row['project_id'];
        debug("Project ID retrieved: $projectId");

        jsonResponse(true, 
            ($action === 'accept' ? 'Partenaire accepté' : 'Partenaire refusé') . ' avec succès', 
            ['project_id' => $projectId]
        );
    } else {
        throw new Exception("Error updating partner status: " . implode(', ', $stmt->errorInfo()));
    }

} catch (Exception $e) {
    debug("Exception caught: " . $e->getMessage());
    jsonResponse(false, "Erreur: " . $e->getMessage());
}