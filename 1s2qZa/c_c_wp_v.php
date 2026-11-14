<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header('Location: index.php');
    exit;
}

require_once('adb.php');

function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
mb_internal_encoding('UTF-8');

function get_unique_filename($directory, $filename) {
    $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
    $file_base = pathinfo($filename, PATHINFO_FILENAME);
    $new_filename = $filename;
    $counter = 1;

    while (file_exists($directory . $new_filename)) {
        $new_filename = $file_base . '_' . $counter . '.' . $file_ext;
        $counter++;
    }

    return $new_filename;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $image_directory = "course_images/";

    $course_name = sanitize_input($_POST["course_name"]);
    $price = intval($_POST["price"]);
    $category_id = intval($_POST["category_id"]);
    $language_id = intval($_POST["language_id"]);
    $contact_info = sanitize_input($_POST["contact_info"]);
    $parts = intval($_POST["parts"]);

    $contact_query = "INSERT INTO Contacts (contact_info) VALUES (:contact_info)";
    $contact_statement = $conn->prepare($contact_query);
    $contact_statement->bindValue(':contact_info', $contact_info);
    $contact_statement->execute();
    $contact_id = $conn->lastInsertId();

    $course_img = $_FILES['course_image']['name'];
    $course_img_temp = $_FILES['course_image']['tmp_name'];
    $course_img = get_unique_filename($image_directory, $course_img);
    move_uploaded_file($course_img_temp, $image_directory . $course_img);

    $course_query = "INSERT INTO Courses (course_img, course_name, price, category_id, language_id, contact_id) VALUES (:course_img, :course_name, :price, :category_id, :language_id, :contact_id)";
    $course_statement = $conn->prepare($course_query);
    $course_statement->bindValue(':course_img', $course_img);
    $course_statement->bindValue(':course_name', $course_name);
    $course_statement->bindValue(':price', $price);
    $course_statement->bindValue(':category_id', $category_id);
    $course_statement->bindValue(':language_id', $language_id);
    $course_statement->bindValue(':contact_id', $contact_id);
    $course_statement->execute();
    $course_id = $conn->lastInsertId();

    for ($i = 1; $i <= $parts; $i++) {
        $part_title = sanitize_input($_POST["part_title_$i"]);
        $video_ids = $_POST["video_id_$i"];

        $part_query = "INSERT INTO CourseParts (course_id, part_title, part_order) VALUES (:course_id, :part_title, :part_order)";
        $part_statement = $conn->prepare($part_query);
        $part_statement->bindValue(':course_id', $course_id);
        $part_statement->bindValue(':part_title', $part_title);
        $part_statement->bindValue(':part_order', $i);
        $part_statement->execute();
        $part_id = $conn->lastInsertId();

        foreach ($video_ids as $key => $video_id) {
            $video_title = sanitize_input($_POST["video_title_${i}_".($key+1)]);
            $video_id = sanitize_input($video_id);

            $video_query = "INSERT INTO CourseVideos (part_id, video_title, video_url, video_order) VALUES (:part_id, :video_title, :video_url, :video_order)";
            $video_statement = $conn->prepare($video_query);
            $video_statement->bindValue(':part_id', $part_id);
            $video_statement->bindValue(':video_title', $video_title);
            $video_statement->bindValue(':video_url', $video_id);
            $video_statement->bindValue(':video_order', $key + 1);
            $video_statement->execute();
        }
    }
    $_SESSION['course_created'] = true;
    header('Location: 1s2qZa-lf.php');
    exit;
}
?>