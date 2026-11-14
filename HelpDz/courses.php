<?php
session_start();

if (!isset($_SESSION['user_r_pseudo'])) {
    header('Location: signin.php');
    exit;
}

$pagetitle = "Mes Courses";
$cssfile1 = '../Css/global.css';
$cssfile2 = '../Css/courses.css';
$logo = '../HomeImg/logo.png';
$homepage = '../index.php';
$formation = 'formation.php';
$finhelp = 'pro_assi.php';
$mycourses = 'courses.php';
$profile = 'profile.php';
$networks = 'networks.php';
$logout = 'logout.php';
$contactus = 'contactus.php';
$newsletter = 'newsletter.php';
$aboutus = 'aboutus.php';
$vm = 'virtual_money.php';
$jsfile1 = '../Js/main.js';
$jsfile2 = 'newsletter.js';
$jsfile3 = '../Js/cs.js';
$gun = 'g_u_n.js';
$jq = '../Js/jquery-3.6.0.min.js';
require_once('uheader.php');

require_once('../functions/db.php');
require_once('../functions/exp.php');

$user_r_pseudo = htmlspecialchars($_SESSION['user_r_pseudo'], ENT_QUOTES, 'UTF-8');

$query = "SELECT 
    Courses.course_id, CONCAT('../1s2qZa/course_images/', Courses.course_img) AS course_img, Courses.course_name, Courses.price
    FROM Courses
    INNER JOIN Orders ON Courses.course_id = Orders.course_id
    INNER JOIN OrderStatus ON Orders.order_status_id = OrderStatus.order_status_id
    WHERE Orders.user_id = (SELECT user_id FROM Users WHERE user_r_pseudo = :user_r_pseudo)
    AND OrderStatus.order_status_description = 'Regarder'
    AND Orders.exp >= CURDATE()";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_r_pseudo', $user_r_pseudo, PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    echo "<section id='my-courses'>";
    echo "<div class='container'>";
    echo "<div class='label'>";
    echo "<h1 data-translate='Mes courses'>Mes courses</h1>";
    echo "</div>";
    echo "<div class='courses'>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $courseImg = htmlspecialchars($row['course_img'], ENT_QUOTES, 'UTF-8');
        $courseName = htmlspecialchars($row['course_name'], ENT_QUOTES, 'UTF-8');
        $coursePrice = htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8');
        $courseId = htmlspecialchars($row['course_id'], ENT_QUOTES, 'UTF-8');

        echo "<div class='course'>";
        echo "<div class='course-img'><img src='$courseImg' alt='$courseName'></div>";
        echo "<div class='course-content'>";
        echo "<div class='course-t'>";
        echo "<div class='course-name'>$courseName</div>";
        echo "</div>";
        echo "<div class='course-price'>$coursePrice <span data-translate='DA'>DA</span></div>";
        echo "</div>";
        echo "<form action='course.php' method='GET'>";
        echo "<input type='hidden' name='course_id' value='$courseId'>";
        echo "<button type='submit' class='watch-button' data-translate='Regarder'>Regarder</button>";
        echo "</form>";
        echo "</div>";
    }
    echo "</div>";
    echo "</div>";
    echo "</section>";
} else {
    echo "<section id='no-course'>";
    echo "<div class='no-courses-message'>";
    echo "<h3 data-translate='Vous n\'avez pas de cours en cours de visionnage.'>Vous n'avez pas de cours en cours de visionnage.</h3>";
    echo "</div>";
    echo "</section>";
}

require_once('footer.php');
?>
