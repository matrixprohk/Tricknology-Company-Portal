<?php

require_once("../config/auth.php");
requireRole("admin");

require_once("../config/database.php");

$stmt = $pdo->query("
SELECT *
FROM departments
ORDER BY id ASC
");

$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<div class="content">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>Departments</h2>

<a href="add-department.php" class="btn btn-primary">

+ Add Department

</a>

</div>

<div class="card shadow">

<div class="card-body">

<table class="table table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Department</th>
<th>Description</th>
<th>Status</th>
<th width="170">Action</th>

</tr>

</thead>

<tbody>

<?php if(count($departments)>0){ ?>

<?php foreach($departments as $row){ ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['department_name']); ?></td>

<td><?= htmlspecialchars($row['description']); ?></td>

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
href="edit-department.php?id=<?= $row['id']; ?>"
class="btn btn-warning btn-sm">

Edit

</a>

<a
href="delete-department.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this department?')">

Delete

</a>

</td>

</tr>

<?php } ?>

<?php } else { ?>

<tr>

<td colspan="5" class="text-center">

No departments found.

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