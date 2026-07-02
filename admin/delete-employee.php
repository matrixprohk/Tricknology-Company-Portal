<?php
/*
|--------------------------------------------------------------------------
| Tricknology Company Portal
|--------------------------------------------------------------------------
| File        : delete-employee.php
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
| Check Employee ID
|--------------------------------------------------------------------------
*/

if (!isset($_GET['id'])) {
    header("Location: employees.php");
    exit;
}

$id = (int)$_GET['id'];

/*
|--------------------------------------------------------------------------
| Check Employee Exists
|--------------------------------------------------------------------------
*/

$check = $pdo->prepare("
    SELECT id
    FROM employees
    WHERE id=?
");

$check->execute([$id]);

$employee = $check->fetch();

if (!$employee) {
    header("Location: employees.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Delete Employee
|--------------------------------------------------------------------------
*/

$delete = $pdo->prepare("
    DELETE FROM employees
    WHERE id=?
");

$delete->execute([$id]);

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header("Location: employees.php?deleted=1");
exit;

?>