<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once('adb.php');
require_once('ch_ad_l.php');

if (!isset($_SESSION['admin_email']) || getAdminLevel($_SESSION['admin_email'], $conn) !== 'main_admin') {
    http_response_code(403);
    exit('Unauthorized');
}

if (!isset($_GET['courseId'])) {
    http_response_code(400);
    exit('Course ID is required');
}

$courseId = intval($_GET['courseId']);

try {
    $stmt = $conn->prepare("SELECT c.course_id, c.course_name, c.price, c.course_img, co.contact_info, c.category_id, c.language_id
                            FROM Courses c 
                            JOIN Contacts co ON c.contact_id = co.contact_id 
                            WHERE c.course_id = ?");
    $stmt->execute([$courseId]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
        http_response_code(404);
        exit('Course not found');
    }

    $stmt = $conn->prepare("SELECT part_id, part_title FROM CourseParts WHERE course_id = ? ORDER BY part_order");
    $stmt->execute([$courseId]);
    $parts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($parts as &$part) {
        $stmt = $conn->prepare("SELECT video_id, video_title, video_url FROM CourseVideos WHERE part_id = ? ORDER BY video_order");
        $stmt->execute([$part['part_id']]);
        $part['videos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $course['parts'] = $parts;

    echo json_encode($course);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database error: ' . $e->getMessage());
}
?><?php
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once('adb.php');
require_once('ch_ad_l.php');

if (!isset($_SESSION['admin_email']) || getAdminLevel($_SESSION['admin_email'], $conn) !== 'main_admin') {
    http_response_code(403);
    exit('Unauthorized');
}

if (!isset($_GET['courseId'])) {
    http_response_code(400);
    exit('Course ID is required');
}

$courseId = intval($_GET['courseId']);

try {
    $stmt = $conn->prepare("SELECT c.course_id, c.course_name, c.price, c.course_img, co.contact_info, c.category_id, c.language_id
                            FROM Courses c 
                            JOIN Contacts co ON c.contact_id = co.contact_id 
                            WHERE c.course_id = ?");
    $stmt->execute([$courseId]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
        http_response_code(404);
        exit('Course not found');
    }

    $stmt = $conn->prepare("SELECT part_id, part_title FROM CourseParts WHERE course_id = ? ORDER BY part_order");
    $stmt->execute([$courseId]);
    $parts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($parts as &$part) {
        $stmt = $conn->prepare("SELECT video_id, video_title, video_url FROM CourseVideos WHERE part_id = ? ORDER BY video_order");
        $stmt->execute([$part['part_id']]);
        $part['videos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $course['parts'] = $parts;

    echo json_encode($course);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database error: ' . $e->getMessage());
}
?>