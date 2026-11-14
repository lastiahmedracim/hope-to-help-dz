<?php
session_start();
require_once('../functions/db.php');

header('Content-Type: application/json');

function sendErrorResponse($message, $statusCode = 400) {
    http_response_code($statusCode);
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

if (!isset($_SESSION['user_r_pseudo'])) {
    sendErrorResponse('Unauthorized', 401);
}

$data = json_decode(file_get_contents('php://input'), true);
$video_id = filter_var($data['video_id'] ?? null, FILTER_VALIDATE_INT);
$token = isset($data['token']) ? htmlspecialchars($data['token'], ENT_QUOTES, 'UTF-8') : null;

if (!$video_id) {
    sendErrorResponse('Invalid input');
}

$user_r_pseudo = htmlspecialchars($_SESSION['user_r_pseudo'], ENT_QUOTES, 'UTF-8');

try {
    $stmt = $pdo->prepare("
        SELECT cv.video_url
        FROM CourseVideos cv
        INNER JOIN CourseParts cp ON cv.part_id = cp.part_id
        INNER JOIN Courses c ON cp.course_id = c.course_id
        INNER JOIN Orders o ON o.course_id = c.course_id
        INNER JOIN Users u ON o.user_id = u.user_id
        WHERE cv.video_id = :video_id 
        AND u.user_r_pseudo = :user_r_pseudo 
        AND o.exp >= CURDATE()
        LIMIT 1
    ");
    $stmt->execute(['video_id' => $video_id, 'user_r_pseudo' => $user_r_pseudo]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && !empty($result['video_url'])) {
        error_log("Fetched video URL for video_id {$video_id}: " . $result['video_url']);
        
        if (preg_match('/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/', $result['video_url'])) {
            echo json_encode(['success' => true, 'video_url' => $result['video_url']]);
        } else {
            error_log("Invalid YouTube URL format for video_id {$video_id}: " . $result['video_url']);
            sendErrorResponse('Invalid video URL format', 400);
        }
    } else {
        error_log("Video not found or access denied for video_id {$video_id} and user {$user_r_pseudo}");
        sendErrorResponse('Video not found or access denied', 404);
    }
} catch (PDOException $e) {
    error_log('Error fetching video URL: ' . $e->getMessage());
    sendErrorResponse('An error occurred', 500);
}
?>