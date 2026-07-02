<?php
/*
|--------------------------------------------------------------------------
| Tricknology Company Portal
|--------------------------------------------------------------------------
| File        : backup-database.php
| Author      : Tapan Hazra
| Channel     : Tricknology
|--------------------------------------------------------------------------
*/

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

include("../config/database.php");

/*
|--------------------------------------------------------------------------
| Backup Folder
|--------------------------------------------------------------------------
*/

$backupFolder = "../backup/";

if (!is_dir($backupFolder)) {
    mkdir($backupFolder, 0777, true);
}

/*
|--------------------------------------------------------------------------
| Database Name
|--------------------------------------------------------------------------
*/

$dbName = "companyportal";

/*
|--------------------------------------------------------------------------
| Backup File
|--------------------------------------------------------------------------
*/

$fileName = $dbName . "_" . date("Y-m-d_H-i-s") . ".sql";

$filePath = $backupFolder . $fileName;

/*
|--------------------------------------------------------------------------
| MySQL Credentials
|--------------------------------------------------------------------------
*/

$dbUser = "root";
$dbPass = "dsf322";

/*
|--------------------------------------------------------------------------
| mysqldump Command
|--------------------------------------------------------------------------
*/

$command =
'mysqldump --user=' .
escapeshellarg($dbUser) .
' --password=' .
escapeshellarg($dbPass) .
' ' .
escapeshellarg($dbName) .
' > ' .
escapeshellarg($filePath);

exec($command, $output, $result);

/*
|--------------------------------------------------------------------------
| Activity Log
|--------------------------------------------------------------------------
*/

if ($result === 0) {

    $log = $pdo->prepare("
        INSERT INTO activity_logs
        (
            user_name,
            activity,
            ip_address
        )
        VALUES
        (
            ?, ?, ?
        )
    ");

    $log->execute([
        $_SESSION['fullname'] ?? 'Administrator',
        'Created Database Backup',
        $_SERVER['REMOTE_ADDR']
    ]);

    header("Location: settings.php?backup=success");
    exit;

} else {

    header("Location: settings.php?backup=failed");
    exit;

}
?>