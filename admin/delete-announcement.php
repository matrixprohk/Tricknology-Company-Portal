<?php
/*
|--------------------------------------------------------------------------
| Tricknology Company Portal
|--------------------------------------------------------------------------
| File        : delete-announcement.php
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
| Check ID
|--------------------------------------------------------------------------
*/

if (!isset($_GET['id'])) {
    header("Location: announcements.php");
    exit;
}

$id = (int)$_GET['id'];

/*
|--------------------------------------------------------------------------
| Check Announcement Exists
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT id
    FROM announcements
    WHERE id=?
");

$stmt->execute([$id]);

$announcement = $stmt->fetch();

if (!$announcement) {
    header("Location: announcements.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Delete Announcement
|--------------------------------------------------------------------------
*/

$delete = $pdo->prepare("
    DELETE FROM announcements
    WHERE id=?
");

$delete->execute([$id]);

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header("Location: announcements.php?deleted=1");
exit;
?>