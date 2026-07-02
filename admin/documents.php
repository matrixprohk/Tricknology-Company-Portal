<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

require_once("../config/database.php");

/*
|--------------------------------------------------------------------------
| Load Documents
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
SELECT
    d.*,
    u.fullname
FROM documents d
LEFT JOIN users u
ON d.uploaded_by = u.id
ORDER BY d.id DESC
");

$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<div class="content">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>

<i class="bi bi-folder2-open"></i>

Documents

</h2>

<a href="upload-document.php" class="btn btn-primary">

<i class="bi bi-upload"></i>

Upload Document

</a>

</div>

<?php if(isset($_GET['added'])){ ?>

<div class="alert alert-success">

Document uploaded successfully.

</div>

<?php } ?>

<?php if(isset($_GET['updated'])){ ?>

<div class="alert alert-success">

Document updated successfully.

</div>

<?php } ?>

<?php if(isset($_GET['deleted'])){ ?>

<div class="alert alert-danger">

Document deleted successfully.

</div>

<?php } ?>

<div class="card shadow">

<div class="card-header">

<h5 class="mb-0">

Company Documents

</h5>

</div>

<div class="card-body">

<table class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th width="60">ID</th>

<th>Title</th>

<th>Description</th>

<th>File Name</th>

<th>Size</th>

<th>Uploaded By</th>

<th>Date</th>

<th width="230">Action</th>

</tr>

</thead>

<tbody>

<?php if(count($documents)>0){ ?>

<?php foreach($documents as $row){ ?>

<tr>

<td>

<?= $row['id']; ?>

</td>

<td>

<?= htmlspecialchars($row['title']); ?>

</td>

<td>

<?= nl2br(htmlspecialchars($row['description'])); ?>

</td>

<td>

<?= htmlspecialchars($row['filename']); ?>

</td>

<td>

<?= number_format($row['filesize']/1024,2); ?> KB

</td>

<td>

<?= htmlspecialchars($row['fullname'] ?? 'Unknown'); ?>

</td>

<td>

<?= date("d M Y", strtotime($row['uploaded_at'])); ?>

</td>

<td>

<a
href="../uploads/documents/<?= urlencode($row['filename']); ?>"
class="btn btn-success btn-sm"
target="_blank">

Download

</a>

<a
href="edit-document.php?id=<?= $row['id']; ?>"
class="btn btn-warning btn-sm">

Edit

</a>

<a
href="delete-document.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this document?');">

Delete

</a>

</td>

</tr>

<?php } ?>

<?php } else { ?>

<tr>

<td colspan="8" class="text-center">

No Documents Found.

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