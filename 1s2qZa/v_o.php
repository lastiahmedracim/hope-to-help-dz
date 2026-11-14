<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header('Location: index.php');
    exit;
}

require_once('adb.php');
require_once('ch_ad_l.php');

$admin_email = $_SESSION['admin_email'];
$admin_level = getAdminLevel($admin_email, $conn);

if ($admin_level === 'main_admin') {

    if (isset($_GET['order_id']) && isset($_GET['exp_date'])) {
        $orderId = $_GET['order_id'];
        $expDate = $_GET['exp_date'];

        try {
            if ($expDate && $orderId) {

                $currentDate = date('Y-m-d');
                $expDateObj = new DateTime($expDate);
                $newStatusId = ($expDateObj->format('Y-m-d') <= $currentDate) ? 3 : 2;

                $stmt = $conn->prepare("UPDATE Orders SET exp = :expDate WHERE order_id = :orderId");
                $stmt->bindParam(':expDate', $expDate);
                $stmt->bindParam(':orderId', $orderId);
                $stmt->execute();

                $statusUpdateStmt = $conn->prepare("UPDATE Orders SET order_status_id = :newStatusId WHERE order_id = :orderId");
                $statusUpdateStmt->bindParam(':newStatusId', $newStatusId);
                $statusUpdateStmt->bindParam(':orderId', $orderId);
                $statusUpdateStmt->execute();

                if ($statusUpdateStmt->rowCount() > 0 || $stmt->rowCount() > 0) {
                    if ($newStatusId == 2) {
                        $getUserStmt = $conn->prepare("SELECT user_id FROM Orders WHERE order_id = :orderId");
                        $getUserStmt->bindParam(':orderId', $orderId);
                        $getUserStmt->execute();
                        $userId = $getUserStmt->fetchColumn();

                        $updateUserStmt = $conn->prepare("UPDATE Users SET bought = TRUE WHERE user_id = :userId AND bought = FALSE");
                        $updateUserStmt->bindParam(':userId', $userId);
                        $updateUserStmt->execute();

                        $getCourseStmt = $conn->prepare("SELECT course_id FROM Orders WHERE order_id = :orderId");
                        $getCourseStmt->bindParam(':orderId', $orderId);
                        $getCourseStmt->execute();
                        $courseId = $getCourseStmt->fetchColumn();

                        $getPriceStmt = $conn->prepare("SELECT price FROM Courses WHERE course_id = :courseId");
                        $getPriceStmt->bindParam(':courseId', $courseId);
                        $getPriceStmt->execute();
                        $coursePrice = $getPriceStmt->fetchColumn();

                        $insertTotalStmt = $conn->prepare("INSERT INTO TotalCourses (c_profit, user_id) VALUES (:coursePrice, :userId)");
                        $insertTotalStmt->bindParam(':coursePrice', $coursePrice);
                        $insertTotalStmt->bindParam(':userId', $userId);
                        $insertTotalStmt->execute();
                    }

                    echo 'succès';
                } else {
                    echo 'Veuillez sélectionner une autre date, soit antérieure soit postérieure à aujourd\'hui.';
                }
            } else {
                echo 'Veuillez sélectionner une date d\'expiration valide.';
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            print_r($stmt->errorInfo());
            print_r($statusUpdateStmt->errorInfo());
            print_r($getUserStmt->errorInfo());
            print_r($updateUserStmt->errorInfo());
            print_r($getCourseStmt->errorInfo());
            print_r($getPriceStmt->errorInfo());
            print_r($insertTotalStmt->errorInfo());
        }

    } else {
        echo 'Invalid request';
    }

}
?>
