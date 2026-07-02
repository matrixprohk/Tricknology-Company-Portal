<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

require_once("../config/database.php");

$message = "";

if (isset($_POST['upload'])) {

    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $uploaded_by = $_SESSION['id'];

    if ($title == "") {

        $message = "
        <div class='alert alert-danger'>
            Please enter document title.
        </div>";

    } else {

        if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {

            $allowed = [
                "pdf",
                "doc",
                "docx",
                "xls",
                "xlsx",
                "ppt",
                "pptx",
                "zip",
                "jpg",
                "jpeg",
                "png"
            ];

            $originalName = $_FILES['document']['name'];
            $tmp          = $_FILES['document']['tmp_name'];
            $size         = $_FILES['document']['size'];

            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            if (!in_array($extension, $allowed)) {

                $message = "
                <div class='alert alert-danger'>
                    Invalid file type.
                </div>";

            } elseif ($size > 10485760) {

                $message = "
                <div class='alert alert-danger'>
                    Maximum upload size is 10 MB.
                </div>";

            } else {

                $uploadDir = "../uploads/documents/";

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $newFileName = time() . "_" .
                    preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);

                if (move_uploaded_file($tmp, $uploadDir . $newFileName)) {

                    $insert = $pdo->prepare("
                        INSERT INTO documents
                        (
                            title,
                            description,
                            filename,
                            filesize,
                            uploaded_by
                        )
                        VALUES
                        (
                            ?,?,?,?,?
                        )
                    ");

                    $insert->execute([
                        $title,
                        $description,
                        $newFileName,
                        $size,
                        $uploaded_by
                    ]);

                    header("Location: documents.php?added=1");
                    exit;

                } else {

                    $message = "
                    <div class='alert alert-danger'>
                        File upload failed.
                    </div>";

                }

            }

        } else {

            $message = "
            <div class='alert alert-danger'>
                Please select a file.
            </div>";

        }

    }

}

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<h2 class="mb-4">Upload Document</h2>

<?= $message ?>

<div class="card shadow">

<div class="card-header">

<h5>Upload Company Document</h5>

</div>

<div class="card-body">

<form method="post" enctype="multipart/form-data">

<div class="row">

<div class="col-md-12 mb-3">

<label class="form-label">

Document Title

</label>

<input
type="text"
name="title"
class="form-control"
required>

</div>

<div class="col-12 mb-3">

<label class="form-label">

Description

</label>

<textarea
name="description"
class="form-control"
rows="5"></textarea>

</div>

<div class="col-12 mb-3">

<label class="form-label">

Choose File

</label>

<input
type="file"
name="document"
class="form-control"
required>

<div class="form-text">

Allowed:
PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, JPG, JPEG, PNG

<br>

Maximum Size: 10 MB

</div>

</div>

</div>

<button
type="submit"
name="upload"
class="btn btn-primary">

<i class="bi bi-upload"></i>

Upload Document

</button>

<a
href="documents.php"
class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

<?php
include("../includes/footer.php");
?>