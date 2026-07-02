<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";

$stmt = $pdo->prepare("
SELECT *
FROM uploads
WHERE uploaded_by=?
ORDER BY id DESC
");

$stmt->execute([
    $_SESSION['fullname']
]);

$uploads = $stmt->fetchAll(PDO::FETCH_ASSOC);

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<div class="content">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>

<i class="bi bi-person-lines-fill"></i>

My Uploads

</h2>

<a href="upload-file.php" class="btn btn-primary">

<i class="bi bi-upload"></i>

Upload New File

</a>

</div>

<div class="card shadow">

<div class="card-header">

<h5 class="mb-0">

Files Uploaded By You

</h5>

</div>

<div class="card-body">

<table class="table table-hover align-middle">

<thead class="table-dark">

<tr>

<th width="60">ID</th>

<th>Title</th>

<th>Category</th>

<th>File Name</th>

<th width="150">Upload Date</th>

<th width="220">Action</th>

</tr>

</thead>

<tbody>

<?php if(count($uploads)>0){ ?>

<?php foreach($uploads as $row){ ?>

<tr>

<td>

<?= $row['id']; ?>

</td>

<td>

<?= htmlspecialchars($row['title']); ?>

</td>

<td>

<?= htmlspecialchars($row['category']); ?>

</td>

<td>

<?= htmlspecialchars($row['filename']); ?>

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

<td colspan="6" class="text-center text-muted">

You have not uploaded any files yet.

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