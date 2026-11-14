<?php
require_once('../functions/db.php');  

$course_id = $_GET['course_id'];

try {
    $stmt = $pdo->prepare("SELECT CourseVideos.video_title, CourseVideos.video_url FROM CourseVideos INNER JOIN CourseParts ON CourseVideos.part_id = CourseParts.part_id WHERE CourseParts.course_id = :course_id");
    $stmt->execute(['course_id' => $course_id]);

    $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($videos);
} catch (PDOException $e) {
    echo 'Error fetching video list: ' . $e->getMessage();
}
?>
