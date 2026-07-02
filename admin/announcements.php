<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

require_once("../config/database.php");

/*
|--------------------------------------------------------------------------
| Load Announcements
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
SELECT
    a.*,
    u.fullname
FROM announcements a
LEFT JOIN users u
ON a.created_by = u.id
ORDER BY a.id DESC
");

$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<div class="content">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>Announcements</h2>

<a href="add-announcement.php" class="btn btn-primary">

<i class="bi bi-plus-circle"></i>

Add Announcement

</a>

</div>

<?php if(isset($_GET['added'])){ ?>

<div class="alert alert-success">

Announcement added successfully.

</div>

<?php } ?>

<?php if(isset($_GET['updated'])){ ?>

<div class="alert alert-success">

Announcement updated successfully.

</div>

<?php } ?>

<?php if(isset($_GET['deleted'])){ ?>

<div class="alert alert-danger">

Announcement deleted successfully.

</div>

<?php } ?>

<div class="card shadow">

<div class="card-body">

<table class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th width="60">ID</th>

<th>Title</th>

<th>Description</th>

<th width="180">Created By</th>

<th width="170">Created</th>

<th width="180">Action</th>

</tr>

</thead>

<tbody>

<?php if(count($announcements)>0){ ?>

<?php foreach($announcements as $row){ ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['title']); ?></td>

<td><?= nl2br(htmlspecialchars($row['description'])); ?></td>

<td>

<?= htmlspecialchars($row['fullname'] ?? 'Unknown'); ?>

</td>

<td>

<?= date("d M Y h:i A", strtotime($row['created_at'])); ?>

</td>

<td>

<a
href="edit-announcement.php?id=<?= $row['id']; ?>"
class="btn btn-warning btn-sm">

Edit

</a>

<a
href="delete-announcement.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this announcement?');">

Delete

</a>

</td>

</tr>

<?php } ?>

<?php } else { ?>

<tr>

<td colspan="6" class="text-center">

No Announcements Found

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