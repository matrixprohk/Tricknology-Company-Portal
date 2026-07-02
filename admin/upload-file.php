<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

require_once("../config/database.php");

$message = "";

if (isset($_POST['upload'])) {

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $uploaded_by = $_SESSION['id'];

    if (!empty($_FILES['file']['name'])) {

        $uploadDir = "../uploads/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $originalName = basename($_FILES['file']['name']);

        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        $allowed = [
            "pdf","doc","docx",
            "xls","xlsx",
            "ppt","pptx",
            "txt",
            "jpg","jpeg","png","gif",
            "zip","rar"
        ];

        if (!in_array($extension, $allowed)) {

            $message = "<div class='alert alert-danger'>
            Invalid file type.
            </div>";

        } else {

            $newFileName = time()."_".preg_replace('/[^A-Za-z0-9._-]/','_',$originalName);

            if(move_uploaded_file($_FILES['file']['tmp_name'],$uploadDir.$newFileName)){

                $stmt = $pdo->prepare("
                INSERT INTO uploads
                (
                    title,
                    description,
                    filename,
                    original_name,
                    filesize,
                    filetype,
                    uploaded_by
                )
                VALUES
                (
                    ?,?,?,?,?,?,?
                )
                ");

                $stmt->execute([

                    $title,
                    $description,
                    $newFileName,
                    $originalName,
                    $_FILES['file']['size'],
                    $_FILES['file']['type'],
                    $uploaded_by

                ]);

                header("Location: upload-center.php?uploaded=1");
                exit;

            }else{

                $message = "<div class='alert alert-danger'>
                Failed to upload file.
                </div>";

            }

        }

    } else {

        $message = "<div class='alert alert-warning'>
        Please choose a file.
        </div>";

    }

}

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<div class="content">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>

Upload File

</h2>

<a href="upload-center.php" class="btn btn-secondary">

Back

</a>

</div>

<?= $message ?>

<div class="card shadow">

<div class="card-body">

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">

<label class="form-label">

Document Title

</label>

<input
type="text"
name="title"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Description

</label>

<textarea
name="description"
class="form-control"
rows="4"></textarea>

</div>

<div class="mb-3">

<label class="form-label">

Choose File

</label>

<input
type="file"
name="file"
class="form-control"
required>

<small class="text-muted">

Allowed:
PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG, ZIP, RAR

</small>

</div>

<button
type="submit"
name="upload"
class="btn btn-primary">

Upload File

</button>

<a
href="upload-center.php"
class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

<?php
include("../includes/footer.php");
?>