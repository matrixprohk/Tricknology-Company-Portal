<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

include("../config/database.php");

/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/

$totalEmployees = $pdo->query("
SELECT COUNT(*) FROM employees
")->fetchColumn();

$totalDepartments = $pdo->query("
SELECT COUNT(*) FROM departments
")->fetchColumn();

$totalDocuments = $pdo->query("
SELECT COUNT(*) FROM documents
")->fetchColumn();

$totalAnnouncements = $pdo->query("
SELECT COUNT(*) FROM announcements
")->fetchColumn();

$totalUsers = $pdo->query("
SELECT COUNT(*) FROM users
")->fetchColumn();

$activeEmployees = $pdo->query("
SELECT COUNT(*)
FROM employees
WHERE status='active'
")->fetchColumn();

$inactiveEmployees = $pdo->query("
SELECT COUNT(*)
FROM employees
WHERE status='disabled'
")->fetchColumn();

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<div class="content">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold">

Dashboard

</h2>

<p class="text-muted">

Welcome back,

<strong>

<?= htmlspecialchars($_SESSION['fullname'] ?? 'Administrator'); ?>

</strong>

👋

</p>

</div>

<div>

<span class="badge bg-primary fs-6">

<?= date("d M Y"); ?>

</span>

</div>

</div>

<!-- Statistics Cards -->

<div class="row g-4">

<div class="col-lg-3 col-md-6">

<div class="card shadow border-0">

<div class="card-body">

<div class="d-flex justify-content-between">

<div>

<h6 class="text-muted">

Employees

</h6>

<h2>

<?= $totalEmployees; ?>

</h2>

</div>

<div>

<i class="bi bi-people-fill text-primary"
style="font-size:40px;"></i>

</div>

</div>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card shadow border-0">

<div class="card-body">

<div class="d-flex justify-content-between">

<div>

<h6 class="text-muted">

Departments

</h6>

<h2>

<?= $totalDepartments; ?>

</h2>

</div>

<div>

<i class="bi bi-building text-success"
style="font-size:40px;"></i>

</div>

</div>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card shadow border-0">

<div class="card-body">

<div class="d-flex justify-content-between">

<div>

<h6 class="text-muted">

Documents

</h6>

<h2>

<?= $totalDocuments; ?>

</h2>

</div>

<div>

<i class="bi bi-folder-fill text-warning"
style="font-size:40px;"></i>

</div>

</div>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card shadow border-0">

<div class="card-body">

<div class="d-flex justify-content-between">

<div>

<h6 class="text-muted">

Users

</h6>

<h2>

<?= $totalUsers; ?>

</h2>

</div>

<div>

<i class="bi bi-person-circle text-danger"
style="font-size:40px;"></i>

</div>

</div>

</div>

</div>

</div>

</div>

<br>

<div class="row g-4">

<div class="col-md-6">

<div class="card shadow border-0">

<div class="card-header bg-success text-white">

Active Employees

</div>

<div class="card-body text-center">

<h1 class="display-3 text-success">

<?= $activeEmployees; ?>

</h1>

<p>

Currently Working

</p>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow border-0">

<div class="card-header bg-danger text-white">

Inactive Employees

</div>

<div class="card-body text-center">

<h1 class="display-3 text-danger">

<?= $inactiveEmployees; ?>

</h1>

<p>

Inactive Employees

</p>

</div>

</div>

</div>

</div>


<?php

/*
|--------------------------------------------------------------------------
| Recent Activities
|--------------------------------------------------------------------------
*/

$logs = $pdo->query("
SELECT
    activity_logs.*,
    users.fullname
FROM activity_logs
LEFT JOIN users
ON activity_logs.user_id = users.id
ORDER BY activity_logs.id DESC
LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Latest Announcements
|--------------------------------------------------------------------------
*/

$announcements = $pdo->query("
SELECT *
FROM announcements
ORDER BY id DESC
LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="row mt-4">

    <!-- Recent Activities -->

    <div class="col-lg-7">

        <div class="card shadow border-0">

            <div class="card-header bg-dark text-white">

                <i class="bi bi-clock-history"></i>

                Recent Activities

            </div>

            <div class="card-body">

                <?php if(count($logs)>0){ ?>

                    <div class="list-group list-group-flush">

                    <?php foreach($logs as $log){ ?>

                        <div class="list-group-item">

                            <div class="d-flex justify-content-between">

                                <div>

                                    <strong>

                                        <?= htmlspecialchars($log['fullname'] ?? 'Unknown User'); ?>

                                    </strong>

                                    <br>

                                    <?= htmlspecialchars($log['activity']); ?>

                                </div>

                                <small class="text-muted">

                                    <?= date("d M h:i A",strtotime($log['created_at'])); ?>

                                </small>

                            </div>

                        </div>

                    <?php } ?>

                    </div>

                <?php } else { ?>

                    <div class="text-center text-muted">

                        No Activity Found

                    </div>

                <?php } ?>

            </div>

        </div>

    </div>



    <!-- Latest Announcements -->

    <div class="col-lg-5">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">

                <i class="bi bi-megaphone-fill"></i>

                Latest Announcements

            </div>

            <div class="card-body">

                <?php if(count($announcements)>0){ ?>

                    <ul class="list-group list-group-flush">

                    <?php foreach($announcements as $row){ ?>

                        <li class="list-group-item">

                            <strong>

                                <?= htmlspecialchars($row['title']); ?>

                            </strong>

                            <br>

                            <small class="text-muted">

                                <?= date("d M Y",strtotime($row['created_at'])); ?>

                            </small>

                            <br><br>

                            <?= nl2br(htmlspecialchars(substr($row['description'] ?? '', 0, 100))); ?>

                            ...

                        </li>

                    <?php } ?>

                    </ul>

                <?php } else { ?>

                    <div class="text-center text-muted">

                        No Announcement Available

                    </div>

                <?php } ?>

            </div>

        </div>

    </div>

</div>

<?php

/*
|--------------------------------------------------------------------------
| Recent Documents
|--------------------------------------------------------------------------
*/

$documents = $pdo->query("
SELECT *
FROM documents
ORDER BY id DESC
LIMIT 5
")->fetchAll();

/*
|--------------------------------------------------------------------------
| Latest Employees
|--------------------------------------------------------------------------
*/

$employees = $pdo->query("
SELECT *
FROM employees
ORDER BY id DESC
LIMIT 5
")->fetchAll();

?>

<div class="row mt-4">

<!-- Employee Status Chart -->

<div class="col-lg-6">

<div class="card shadow border-0">

<div class="card-header bg-info text-white">

<i class="bi bi-bar-chart-fill"></i>

Employee Status

</div>

<div class="card-body">

<canvas id="employeeChart" height="220"></canvas>

</div>

</div>

</div>

<!-- Recently Uploaded Documents -->

<div class="col-lg-6">

<div class="card shadow border-0">

<div class="card-header bg-warning">

<i class="bi bi-folder-fill"></i>

Recently Uploaded Documents

</div>

<div class="card-body">

<?php if(count($documents)>0){ ?>

<ul class="list-group list-group-flush">

<?php foreach($documents as $doc){ ?>

<li class="list-group-item">

<div class="d-flex justify-content-between">

<div>

<strong>

<?= htmlspecialchars($doc['title']); ?>

</strong>

<br>

<small class="text-muted">

<?= number_format($doc['filesize']/1024,2); ?> KB

</small>

</div>

<a
href="../uploads/<?= urlencode($doc['filename']); ?>"
target="_blank"
class="btn btn-success btn-sm">

Download

</a>

</div>

</li>

<?php } ?>

</ul>

<?php } else { ?>

<div class="text-center text-muted">

No Documents Found

</div>

<?php } ?>

</div>

</div>

</div>

</div>

<br>

<div class="row">

<!-- Latest Employees -->

<div class="col-lg-12">

<div class="card shadow border-0">

<div class="card-header bg-secondary text-white">

<i class="bi bi-people-fill"></i>

Latest Employees

</div>

<div class="card-body">

<table class="table table-hover">

<thead>

<tr>

<th>Employee ID</th>

<th>Name</th>

<th>Email</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php if(count($employees)>0){ ?>

<?php foreach($employees as $emp){ ?>

<tr>

<td>

<?= htmlspecialchars($emp['employee_code']); ?>

</td>

<td>

<?= htmlspecialchars($emp['fullname']); ?>

</td>

<td>

<?= htmlspecialchars($emp['email']); ?>

</td>

<td>

<?php if($emp['status']=="disabled"){ ?>

<span class="badge bg-success">

Active

</span>

<?php }else{ ?>

<span class="badge bg-danger">

Disabled

</span>

<?php } ?>

</td>

</tr>

<?php } ?>

<?php } else { ?>

<tr>

<td colspan="4" class="text-center">

No Employees Found

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<!-- Chart.js -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx = document.getElementById('employeeChart');

new Chart(ctx,{

type:'doughnut',

data:{

labels:[
'Active',
'Inactive'
],

datasets:[{

data:[
<?= $activeEmployees ?>,
<?= $inactiveEmployees ?>
],

backgroundColor:[
'#198754',
'#dc3545'
]

}]

},

options:{

responsive:true,

plugins:{

legend:{

position:'bottom'

}

}

}

});

</script>


<?php

/*
|--------------------------------------------------------------------------
| Company Information
|--------------------------------------------------------------------------
*/

$company = $pdo->query("
SELECT *
FROM settings
LIMIT 1
")->fetch();

?>

<div class="row mt-4">

<!-- Quick Actions -->

<div class="col-lg-6">

<div class="card shadow border-0">

<div class="card-header bg-success text-white">

<i class="bi bi-lightning-fill"></i>

Quick Actions

</div>

<div class="card-body">

<div class="row g-3">

<div class="col-md-6">

<a href="add-employee.php"
class="btn btn-primary w-100">

<i class="bi bi-person-plus-fill"></i>

Add Employee

</a>

</div>

<div class="col-md-6">

<a href="add-department.php"
class="btn btn-success w-100">

<i class="bi bi-building-add"></i>

Add Department

</a>

</div>

<div class="col-md-6">

<a href="upload-document.php"
class="btn btn-warning w-100">

<i class="bi bi-upload"></i>

Upload Document

</a>

</div>

<div class="col-md-6">

<a href="add-announcement.php"
class="btn btn-info w-100 text-white">

<i class="bi bi-megaphone-fill"></i>

Announcement

</a>

</div>

<div class="col-md-6">

<a href="backup-database.php"
class="btn btn-dark w-100">

<i class="bi bi-download"></i>

Backup

</a>

</div>

<div class="col-md-6">

<a href="users.php"
class="btn btn-secondary w-100">

<i class="bi bi-people-fill"></i>

Manage Users

</a>

</div>

</div>

</div>

</div>

</div>

<!-- Company Information -->

<div class="col-lg-6">

<div class="card shadow border-0">

<div class="card-header bg-primary text-white">

<i class="bi bi-building"></i>

Company Information

</div>

<div class="card-body text-center">

<?php

if(
!empty($company['company_logo']) &&
file_exists("../assets/images/".$company['company_logo'])
)
{

?>

<img
src="../assets/images/<?= htmlspecialchars($company['company_logo']); ?>"
style="width:90px;height:90px;border-radius:50%;object-fit:cover;">

<?php

}

?>

<h4 class="mt-3">

<?= htmlspecialchars($company['company_name']); ?>

</h4>

<p class="text-muted">

<?= htmlspecialchars($company['company_email']); ?>

</p>

<p>

<i class="bi bi-telephone-fill"></i>

<?= htmlspecialchars($company['company_phone']); ?>

</p>

<p>

<i class="bi bi-globe"></i>

<a
href="<?= htmlspecialchars($company['company_website']); ?>"
target="_blank">

<?= htmlspecialchars($company['company_website']); ?>

</a>

</p>

<a
href="settings.php"
class="btn btn-outline-primary">

<i class="bi bi-gear-fill"></i>

Settings

</a>

</div>

</div>

</div>

</div>

<br>

<!-- Footer Statistics -->

<div class="card shadow border-0">

<div class="card-body">

<div class="row text-center">

<div class="col-md-3">

<h5>

<?= $totalEmployees; ?>

</h5>

<p class="text-muted">

Employees

</p>

</div>

<div class="col-md-3">

<h5>

<?= $totalDepartments; ?>

</h5>

<p class="text-muted">

Departments

</p>

</div>

<div class="col-md-3">

<h5>

<?= $totalDocuments; ?>

</h5>

<p class="text-muted">

Documents

</p>

</div>

<div class="col-md-3">

<h5>

<?= $totalAnnouncements; ?>

</h5>

<p class="text-muted">

Announcements

</p>

</div>

</div>

</div>

</div>

</div>

<?php
include("../includes/footer.php");
?>
