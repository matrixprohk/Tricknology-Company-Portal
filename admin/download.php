<?php
/*
|--------------------------------------------------------------------------
| Tricknology Company Portal
|--------------------------------------------------------------------------
| File        : download.php
|--------------------------------------------------------------------------
*/

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";

if (!isset($_GET['id'])) {
    exit("Invalid Request");
}

$id = (int)$_GET['id'];

/*
|--------------------------------------------------------------------------
| Get File Information
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
    exit("File not found.");
}

$filePath = "../uploads/" . $file['filename'];

if (!file_exists($filePath)) {
    exit("File does not exist.");
}

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

"Downloaded file : " . $file['title'],

$_SERVER['REMOTE_ADDR']

]);

/*
|--------------------------------------------------------------------------
| Download File
|--------------------------------------------------------------------------
*/

header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"" . basename($file['filename']) . "\"");
header("Content-Length: " . filesize($filePath));
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate");

readfile($filePath);
exit;
?>