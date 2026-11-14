<?php
function getAdminLevel($email, $pdo) {
    $query = "SELECT admin_level FROM Admins WHERE admin_email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['admin_level'] ?? 'non_admin';
}
?>