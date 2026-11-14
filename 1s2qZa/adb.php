<?php
$servername = "shareddb-h.hosting.stackcp.net";
$username = "Hopetohelpdz-3735ad41";
$password= "a87v54trea";
$dbname = "Hopetohelpdz-3735ad41";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password, array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ));
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>