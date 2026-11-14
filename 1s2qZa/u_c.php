<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');
require_once('adb.php');

if (!isset($_SESSION['admin_email'])) {
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit;
}

function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseId = filter_var($_POST['courseId'], FILTER_VALIDATE_INT);
    $courseName = sanitize_input($_POST['courseName']);
    $coursePrice = filter_var($_POST['coursePrice'], FILTER_VALIDATE_FLOAT);
    $courseContact = sanitize_input($_POST['courseContact']);

    try {
        $conn->beginTransaction();

        $updateCourseQuery = "UPDATE Courses SET course_name = :courseName, price = :coursePrice WHERE course_id = :courseId";
        $stmt = $conn->prepare($updateCourseQuery);
        $stmt->bindParam(':courseName', $courseName, PDO::PARAM_STR);
        $stmt->bindParam(':coursePrice', $coursePrice, PDO::PARAM_STR);
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->execute();

        $updateContactQuery = "UPDATE Contacts SET contact_info = :contactInfo WHERE contact_id = (SELECT contact_id FROM Courses WHERE course_id = :courseId)";
        $stmt = $conn->prepare($updateContactQuery);
        $stmt->bindParam(':contactInfo', $courseContact, PDO::PARAM_STR);
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->execute();

        if (isset($_FILES['courseImage']) && $_FILES['courseImage']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'course_images/';
            $fileExtension = pathinfo($_FILES['courseImage']['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid('course_', true) . '.' . $fileExtension;
            $uploadFile = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['courseImage']['tmp_name'], $uploadFile)) {
                $updateImageQuery = "UPDATE Courses SET course_img = :courseImg WHERE course_id = :courseId";
                $stmt = $conn->prepare($updateImageQuery);
                $stmt->bindParam(':courseImg', $newFileName, PDO::PARAM_STR);
                $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        if (isset($_POST['partTitle']) && is_array($_POST['partTitle'])) {
            $deleteVideosQuery = "DELETE v FROM CourseVideos v 
                                  JOIN CourseParts p ON v.part_id = p.part_id 
                                  WHERE p.course_id = :courseId";
            $stmt = $conn->prepare($deleteVideosQuery);
            $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
            $stmt->execute();

            $deletePartsQuery = "DELETE FROM CourseParts WHERE course_id = :courseId";
            $stmt = $conn->prepare($deletePartsQuery);
            $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
            $stmt->execute();

            foreach ($_POST['partTitle'] as $partIndex => $partTitle) {
                $partTitle = sanitize_input($partTitle);
                $partOrder = $partIndex + 1;

                $insertPartQuery = "INSERT INTO CourseParts (course_id, part_title, part_order) 
                                    VALUES (:courseId, :partTitle, :partOrder)";
                $stmt = $conn->prepare($insertPartQuery);
                $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
                $stmt->bindParam(':partTitle', $partTitle, PDO::PARAM_STR);
                $stmt->bindParam(':partOrder', $partOrder, PDO::PARAM_INT);
                $stmt->execute();

                $partId = $conn->lastInsertId();

                if (isset($_POST['videoTitle'][$partIndex]) && is_array($_POST['videoTitle'][$partIndex])) {
                    foreach ($_POST['videoTitle'][$partIndex] as $videoIndex => $videoTitle) {
                        $videoTitle = sanitize_input($videoTitle);
                        $videoId = sanitize_input($_POST['videoId'][$partIndex][$videoIndex]);
                        $videoOrder = $videoIndex + 1;

                        $insertVideoQuery = "INSERT INTO CourseVideos (part_id, video_title, video_url, video_order) 
                                             VALUES (:partId, :videoTitle, :videoId, :videoOrder)";
                        $stmt = $conn->prepare($insertVideoQuery);
                        $stmt->bindParam(':partId', $partId, PDO::PARAM_INT);
                        $stmt->bindParam(':videoTitle', $videoTitle, PDO::PARAM_STR);
                        $stmt->bindParam(':videoId', $videoId, PDO::PARAM_STR);
                        $stmt->bindParam(':videoOrder', $videoOrder, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }
            }
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Cours mis à jour avec succès']);
    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Error updating course: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Une erreur est survenue lors de la mise à jour du cours']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode de requête non valide']);
}
?>