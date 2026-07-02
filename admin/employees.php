<?php

require_once("../config/auth.php");
requireRole("admin");

require_once("../config/database.php");

$sql = "
SELECT
    e.*,
    d.department_name
FROM employees e
LEFT JOIN departments d
ON e.department_id=d.id
ORDER BY e.id DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<div class="content">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>Employees</h2>

<a href="add-employee.php" class="btn btn-primary">

<i class="bi bi-plus-circle"></i>

Add Employee

</a>

</div>

<div class="card shadow">

<div class="card-body">

<?php if(isset($_GET['added'])){ ?>

<div class="alert alert-success">

Employee Added Successfully.

</div>

<?php } ?>

<?php if(isset($_GET['updated'])){ ?>

<div class="alert alert-success">

Employee Updated Successfully.

</div>

<?php } ?>

<?php if(isset($_GET['deleted'])){ ?>

<div class="alert alert-danger">

Employee Deleted Successfully.

</div>

<?php } ?>

<table class="table table-hover table-bordered align-middle">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Employee Code</th>

<th>Full Name</th>

<th>Department</th>

<th>Designation</th>

<th>Email</th>

<th>Phone</th>

<th>Status</th>

<th width="170">

Action

</th>

</tr>

</thead>

<tbody>

<?php if(count($employees)>0){ ?>

<?php foreach($employees as $row){ ?>

<tr>

<td>

<?= $row['id']; ?>

</td>

<td>

<?= htmlspecialchars($row['employee_code']); ?>

</td>

<td>

<?= htmlspecialchars($row['fullname']); ?>

</td>

<td>

<?= htmlspecialchars($row['department_name'] ?? "N/A"); ?>

</td>

<td>

<?= htmlspecialchars($row['designation']); ?>

</td>

<td>

<?= htmlspecialchars($row['email']); ?>

</td>

<td>

<?= htmlspecialchars($row['phone']); ?>

</td>

<td>

<?php if($row['status']=="active"){ ?>

<span class="badge bg-success">

Active

</span>

<?php } else { ?>

<span class="badge bg-danger">

Disabled

</span>

<?php } ?>

</td>

<td>

<a
href="edit-employee.php?id=<?= $row['id']; ?>"
class="btn btn-warning btn-sm">

Edit

</a>

<a
href="delete-employee.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this employee?')">

Delete

</a>

</td>

</tr>

<?php } ?>

<?php } else { ?>

<tr>

<td colspan="9" class="text-center">

No Employees Found

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<?php
include("../includes/footer.php");
?>