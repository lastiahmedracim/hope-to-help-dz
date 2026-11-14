<?php
require_once('../functions/db.php');
require_once('../functions/exp.php');
session_start();
header('Content-Type: text/html; charset=utf-8');
$translations = [
    'fr' => [
        'Acheter' => 'Acheter',
        'En attente' => 'En attente',
        'Regarder' => 'Regarder',
        'Aucun cours disponible pour les filtres sélectionnés.' => 'Aucun cours disponible pour les filtres sélectionnés.'
    ],
    'ar' => [
        'Acheter' => 'شراء',
        'En attente' => 'قيد الانتظار',
        'Regarder' => 'مشاهدة',
        'Aucun cours disponible pour les filtres sélectionnés.' => 'لا توجد دورات متاحة للفلاتر المحددة.'
    ],
    'en' => [
        'Acheter' => 'Buy',
        'En attente' => 'Pending',
        'Regarder' => 'Watch',
        'Aucun cours disponible pour les filtres sélectionnés.' => 'No courses available for the selected filters.'
    ]
];

function translate($key, $lang) {
    global $translations;
    return $translations[$lang][$key] ?? $key;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selectedCategories = isset($_POST['category']) ? $_POST['category'] : [];
    $selectedLanguages = isset($_POST['language']) ? $_POST['language'] : [];
    $user_r_pseudo = $_SESSION['user_r_pseudo'];
    $lang = isset($_COOKIE['language']) ? $_COOKIE['language'] : 'fr';

    $conditions = "";
    if (!empty($selectedCategories)) {
        $categoryValues = array_map(function ($value) use ($pdo) {
            return $pdo->quote(htmlspecialchars($value));
        }, (array) $selectedCategories);
        $categoryList = implode(",", $categoryValues);
        $conditions .= " AND c.category_id IN (SELECT category_id FROM Categories WHERE category_name IN ($categoryList))";
    }
    if (!empty($selectedLanguages)) {
        $languageValues = array_map(function ($value) use ($pdo) {
            return $pdo->quote(htmlspecialchars($value));
        }, (array) $selectedLanguages);
        $languageList = implode(",", $languageValues);
        $conditions .= " AND c.language_id IN (SELECT language_id FROM Languages WHERE language_name IN ($languageList))";
    }

    $query = "SELECT 
            c.course_id, 
            CONCAT('../1s2qZa/course_images/', c.course_img) AS course_img, 
            c.course_name, 
            c.price, 
            CASE
                WHEN lo.order_status_id IS NULL THEN 'Acheter'
                WHEN lo.order_status_id = 1 THEN 'En attente'
                WHEN lo.order_status_id = 2 THEN 'Regarder'
                ELSE 'Acheter'
            END AS course_status
        FROM Courses c
        LEFT JOIN Users u ON u.user_r_pseudo = :user_r_pseudo
        LEFT JOIN (
            SELECT o1.*
            FROM Orders o1
            INNER JOIN (
                SELECT course_id, user_id, MAX(order_id) as latest_order_id
                FROM Orders
                GROUP BY course_id, user_id
            ) o2 ON o1.order_id = o2.latest_order_id
        ) lo ON lo.course_id = c.course_id AND lo.user_id = u.user_id
        WHERE 1 $conditions
        ORDER BY c.course_id";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_r_pseudo', $user_r_pseudo, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='course'>";
            echo "<div class='course-img'><img src='" . htmlspecialchars($row["course_img"]) . "' alt=''></div>";
            echo "<div class='course-content'>";
            echo "<div class='course-t'>";
            echo "<div class='course-name'>" . htmlspecialchars($row["course_name"]) . "</div>";
            echo "</div>";
            echo "<div class='course-price'>" . htmlspecialchars($row["price"]) . " DA</div>";
            echo "</div>";
            $courseId = htmlspecialchars($row["course_id"]);
            
            switch ($row['course_status']) {
                case 'Regarder':
                    echo "<form action='course.php' method='GET'>";
                    echo "<input type='hidden' name='course_id' value='$courseId'>";
                    echo "<button type='submit' class='watch-button' data-translate>" . translate('Regarder', $lang) . "</button>";
                    echo "</form>";
                    break;
                case 'En attente':
                    echo "<button class='watch-button' disabled data-translate>" . translate('En attente', $lang) . "</button>";
                    break;
                case 'Acheter':
                    echo "<button onclick='buyCourse($courseId)' class='watch-button' data-translate>" . translate('Acheter', $lang) . "</button>";
                    break;
            }
            echo "</div>";
        }
    } else {
        echo "<span data-translate>" . translate('Aucun cours disponible pour les filtres sélectionnés.', $lang) . "</span>";
    }
} else {
    echo "Formulaire non soumis.";
}
?>