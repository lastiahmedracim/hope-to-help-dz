<?php
$dsn = 'mysql:host=shareddb-h.hosting.stackcp.net;dbname=Hopetohelpdz-3735ad41;charset=utf8';
$username = 'Hopetohelpdz-3735ad41';
$password = 'a87v54trea';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $GLOBALS['pdo'] = $pdo;
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>