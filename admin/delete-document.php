<?php
/*
|--------------------------------------------------------------------------
| Tricknology Company Portal
|--------------------------------------------------------------------------
| File        : delete-document.php
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
| Check Document ID
|--------------------------------------------------------------------------
*/

if (!isset($_GET['id'])) {
    header("Location: documents.php");
    exit;
}

$id = (int)$_GET['id'];

/*
|--------------------------------------------------------------------------
| Load Document
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT *
    FROM documents
    WHERE id=?
");

$stmt->execute([$id]);

$document = $stmt->fetch();

if (!$document) {
    header("Location: documents.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Delete Physical File
|--------------------------------------------------------------------------
*/

$file = "../uploads/documents/" . $document['file_name'];

if (file_exists($file)) {
    unlink($file);
}

/*
|--------------------------------------------------------------------------
| Delete Database Record
|--------------------------------------------------------------------------
*/

$delete = $pdo->prepare("
    DELETE
    FROM documents
    WHERE id=?
");

$delete->execute([$id]);

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header("Location: documents.php?deleted=1");
exit;

?>