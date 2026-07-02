<?php
/*
|--------------------------------------------------------------------------
| Tricknology Company Portal
|--------------------------------------------------------------------------
| File : navbar.php
|--------------------------------------------------------------------------
*/
?>

<?php
$currentUser = $_SESSION['fullname'] ?? "Administrator";
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">

<div class="container-fluid">

<a class="navbar-brand fw-bold" href="dashboard.php">

<i class="bi bi-building"></i>

Company Portal

</a>

<div class="ms-auto d-flex align-items-center">

<span class="text-white me-4">

<i class="bi bi-person-circle"></i>

<?= htmlspecialchars($currentUser); ?>

</span>

<a href="../logout.php" class="btn btn-danger">

<i class="bi bi-box-arrow-right"></i>

Logout

</a>

</div>

</div>

</nav>