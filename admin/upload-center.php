<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

require_once("../config/database.php");

/*
|--------------------------------------------------------------------------
| Load Uploads
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
SELECT
    u.*,
    users.fullname
FROM uploads u
LEFT JOIN users
ON u.uploaded_by = users.id
ORDER BY u.id DESC
");

$uploads = $stmt->fetchAll(PDO::FETCH_ASSOC);

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<div class="content">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>

<i class="bi bi-cloud-upload-fill"></i>

Upload Center

</h2>

<a href="upload-file.php" class="btn btn-primary">

<i class="bi bi-plus-circle"></i>

Upload File

</a>

</div>

<?php if(isset($_GET['uploaded'])){ ?>

<div class="alert alert-success">

File uploaded successfully.

</div>

<?php } ?>

<?php if(isset($_GET['updated'])){ ?>

<div class="alert alert-success">

File updated successfully.

</div>

<?php } ?>

<?php if(isset($_GET['deleted'])){ ?>

<div class="alert alert-danger">

File deleted successfully.

</div>

<?php } ?>

<div class="card shadow">

<div class="card-header">

<h5 class="mb-0">

Uploaded Files

</h5>

</div>

<div class="card-body">

<table class="table table-hover table-bordered align-middle">

<thead class="table-dark">

<tr>

<th width="60">ID</th>

<th>Title</th>

<th>Description</th>

<th>Original File</th>

<th>Size</th>

<th>Uploaded By</th>

<th>Date</th>

<th width="240">Action</th>

</tr>

</thead>

<tbody>

<?php if(count($uploads)>0){ ?>

<?php foreach($uploads as $row){ ?>

<tr>

<td><?= $row['id']; ?></td>

<td><?= htmlspecialchars($row['title']); ?></td>

<td><?= htmlspecialchars($row['description']); ?></td>

<td><?= htmlspecialchars($row['original_name']); ?></td>

<td>

<?= number_format($row['filesize']/1024,2); ?> KB

</td>

<td>

<?= htmlspecialchars($row['fullname'] ?? 'Unknown'); ?>

</td>

<td>

<?= date("d M Y",strtotime($row['uploaded_at'])); ?>

</td>

<td>

<a
href="download.php?id=<?= $row['id']; ?>"
class="btn btn-success btn-sm">

<i class="bi bi-download"></i>

Download

</a>

<a
href="edit-upload.php?id=<?= $row['id']; ?>"
class="btn btn-primary btn-sm">

<i class="bi bi-pencil-square"></i>

Edit

</a>

<a
href="delete-upload.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this file?');">

<i class="bi bi-trash"></i>

Delete

</a>

</td>

</tr>

<?php } ?>

<?php } else { ?>

<tr>

<td colspan="8" class="text-center">

No uploaded files found.

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