<?php
function updateExpiredOrders($pdo) {
    $currentDate = date('Y-m-d');
    $query = "UPDATE Orders
              SET order_status_id = (SELECT order_status_id FROM OrderStatus WHERE order_status_description = 'Acheter')
              WHERE exp < :currentDate
              AND order_status_id = (SELECT order_status_id FROM OrderStatus WHERE order_status_description = 'regarder')";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':currentDate', $currentDate);
    $stmt->execute();
}

updateExpiredOrders($pdo);
?>