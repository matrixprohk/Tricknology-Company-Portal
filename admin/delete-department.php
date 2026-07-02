<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

include("../config/database.php");

if (!isset($_GET['id'])) {
    header("Location: departments.php");
    exit;
}

$id = (int)$_GET['id'];

// Check if department exists
$stmt = $pdo->prepare("SELECT * FROM departments WHERE id=?");
$stmt->execute([$id]);

$department = $stmt->fetch();

if (!$department) {
    header("Location: departments.php");
    exit;
}

// Delete department
$delete = $pdo->prepare("DELETE FROM departments WHERE id=?");
$delete->execute([$id]);

header("Location: departments.php?deleted=1");
exit;
?>