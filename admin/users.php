<?php

require_once("../config/auth.php");
requireRole("admin");

require_once("../config/database.php");

/*
|--------------------------------------------------------------------------
| Load Users
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
SELECT *
FROM users
ORDER BY id DESC
");

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");

?>

<div class="content">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="mb-1">

<i class="bi bi-people-fill"></i>

Users Management

</h2>

<p class="text-muted mb-0">

Manage all portal users

</p>

</div>

<a href="register.php" class="btn btn-primary">

<i class="bi bi-person-plus-fill"></i>

Add User

</a>

</div>

<?php if(isset($_GET['added'])){ ?>

<div class="alert alert-success">

User created successfully.

</div>

<?php } ?>

<?php if(isset($_GET['updated'])){ ?>

<div class="alert alert-success">

User updated successfully.

</div>

<?php } ?>

<?php if(isset($_GET['deleted'])){ ?>

<div class="alert alert-danger">

User deleted successfully.

</div>

<?php } ?>

<div class="card shadow border-0">

<div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

<h5 class="mb-0">

<i class="bi bi-people-fill"></i>

All Users

</h5>

<span class="badge bg-light text-dark">

<?= count($users); ?> Users

</span>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover table-bordered align-middle">

<thead class="table-dark">

<tr>

<th width="70">ID</th>

<th>Full Name</th>

<th>Username</th>

<th>Email</th>

<th width="120">Role</th>

<th width="120">Status</th>

<th width="180">Action</th>

</tr>

</thead>

<tbody>

<?php if(count($users)>0){ ?>

<?php foreach($users as $row){ ?>

<tr>

<td>

<?= $row['id']; ?>

</td>

<td>

<?= htmlspecialchars($row['fullname']); ?>

</td>

<td>

<?= htmlspecialchars($row['username']); ?>

</td>

<td>

<?= htmlspecialchars($row['email']); ?>

</td>

<td>

<?php if(strtolower($row['role'])=="admin"){ ?>

<span class="badge bg-danger">

Administrator

</span>

<?php }else{ ?>

<span class="badge bg-primary">

User

</span>

<?php } ?>

</td>

<td>

<?php if(strtolower($row['status'])=="active"){ ?>

<span class="badge bg-success">

Active

</span>

<?php }else{ ?>

<span class="badge bg-secondary">

Disabled

</span>

<?php } ?>

</td>

<td>

<a
href="edit-user.php?id=<?= $row['id']; ?>"
class="btn btn-warning btn-sm">

<i class="bi bi-pencil-square"></i>

Edit

</a>

<a
href="delete-user.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this user?');">

<i class="bi bi-trash"></i>

Delete

</a>

</td>

</tr>

<?php } ?>

<?php }else{ ?>

<tr>

<td colspan="7" class="text-center text-muted">

No users found.

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php
include("../includes/footer.php");
?>