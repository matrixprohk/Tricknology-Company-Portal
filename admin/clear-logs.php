<?php
/*
|--------------------------------------------------------------------------
| Tricknology Company Portal
|--------------------------------------------------------------------------
| File        : clear-logs.php
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
| Clear All Activity Logs
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    DELETE FROM activity_logs
");

$stmt->execute();

/*
|--------------------------------------------------------------------------
| Log This Action
|--------------------------------------------------------------------------
*/

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
    'Cleared all activity logs',
    $_SERVER['REMOTE_ADDR']
]);

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header("Location: logs.php?cleared=1");
exit;
?>