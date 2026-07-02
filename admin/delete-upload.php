<?php

require_once("../config/auth.php");
requireRole("admin");

require_once("../config/database.php");

if (!isset($_GET['id'])) {
    header("Location: upload-center.php");
    exit;
}

$id = (int)$_GET['id'];

/*
|--------------------------------------------------------------------------
| Get File Details
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT *
FROM uploads
WHERE id=?
");

$stmt->execute([$id]);

$file = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$file) {
    header("Location: upload-center.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Delete Physical File
|--------------------------------------------------------------------------
*/

$filePath = "../uploads/" . $file['filename'];

if (file_exists($filePath)) {
    unlink($filePath);
}

/*
|--------------------------------------------------------------------------
| Delete Database Record
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
DELETE FROM uploads
WHERE id=?
");

$stmt->execute([$id]);

/*
|--------------------------------------------------------------------------
| Activity Log
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
?,?,?
)
");

$log->execute([

$_SESSION['fullname'] ?? "Administrator",

"Deleted uploaded file : " . $file['title'],

$_SERVER['REMOTE_ADDR']

]);

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header("Location: upload-center.php?deleted=1");
exit;
?>