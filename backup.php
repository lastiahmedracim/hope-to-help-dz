<?php
$dbHost = 'shareddb-h.hosting.stackcp.net';
$dbUsername = 'Hopetohelpdz-3735ad41';
$dbPassword = 'a87v54trea';
$dbName = 'Hopetohelpdz-3735ad41';

$backupDir = '/home/sites/38b/e/edc8541e5e/backups/';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

$date = date('Y-m-d');
$backupFile = $backupDir . "backup_$date.sql";

$command = "mysqldump --host=$dbHost --user=$dbUsername --password=$dbPassword $dbName > $backupFile";

exec($command, $output, $returnVar);

if ($returnVar === 0) {
    echo "Backup successful: $backupFile";
} else {
    echo "Backup failed!";
}
?>
