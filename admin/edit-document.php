<?php

require_once("../config/auth.php");
requireRole(["admin","user"]);

require_once("../config/database.php");

if (!isset($_GET['id'])) {
    header("Location: documents.php");
    exit;
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("
SELECT *
FROM documents
WHERE id=?
");

$stmt->execute([$id]);

$document = $stmt->fetch();

if(!$document)
{
    header("Location: documents.php");
    exit;
}

$message="";

if(isset($_POST['update']))
{

    $title=trim($_POST['title']);
    $category=trim($_POST['category']);
    $description=trim($_POST['description']);

    $filename=$document['file_name'];

    if($title=="" || $category=="")
    {

        $message="
        <div class='alert alert-danger'>
        Please fill all required fields.
        </div>";

    }
    else
    {

        if(isset($_FILES['document']) &&
        $_FILES['document']['error']==0)
        {

            $allowed=[
                "pdf","doc","docx",
                "xls","xlsx",
                "zip",
                "jpg","jpeg","png"
            ];

            $ext=strtolower(pathinfo(
                $_FILES['document']['name'],
                PATHINFO_EXTENSION
            ));

            if(in_array($ext,$allowed))
            {

                if($_FILES['document']['size']<=10485760)
                {

                    if(file_exists("../uploads/documents/".$filename))
                    {
                        unlink("../uploads/documents/".$filename);
                    }

                    $filename=time()."_".preg_replace(
                        '/[^A-Za-z0-9._-]/',
                        '_',
                        $_FILES['document']['name']
                    );

                    move_uploaded_file(
                        $_FILES['document']['tmp_name'],
                        "../uploads/documents/".$filename
                    );

                }
                else
                {

                    $message="
                    <div class='alert alert-danger'>
                    Maximum File Size is 10MB.
                    </div>";

                }

            }
            else
            {

                $message="
                <div class='alert alert-danger'>
                Invalid File Type.
                </div>";

            }

        }

        if($message=="")
        {

            $update=$pdo->prepare("
            UPDATE documents
            SET

            title=?,
            category=?,
            description=?,
            file_name=?

            WHERE id=?

            ");

            $update->execute([

                $title,
                $category,
                $description,
                $filename,
                $id

            ]);

            header("Location: documents.php?updated=1");

            exit;

        }

    }

}

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<h2 class="mb-4">

Edit Document

</h2>

<?php

echo $message;

?>

<div class="card shadow">

<div class="card-header">

<h5>

Edit Document

</h5>

</div>

<div class="card-body">

<form
method="post"
enctype="multipart/form-data">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Document Title

</label>

<input
type="text"
name="title"
class="form-control"
value="<?= htmlspecialchars($document['title']); ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Category

</label>

<select
name="category"
class="form-select"
required>

<option value="HR"
<?= ($document['category']=="HR") ? "selected" : ""; ?>>

HR

</option>

<option value="IT"
<?= ($document['category']=="IT") ? "selected" : ""; ?>>

IT

</option>

<option value="Finance"
<?= ($document['category']=="Finance") ? "selected" : ""; ?>>

Finance

</option>

<option value="Administration"
<?= ($document['category']=="Administration") ? "selected" : ""; ?>>

Administration

</option>

<option value="General"
<?= ($document['category']=="General") ? "selected" : ""; ?>>

General

</option>

</select>

</div>

<div class="col-12 mb-3">

<label class="form-label">

Description

</label>

<textarea
name="description"
class="form-control"
rows="5"><?= htmlspecialchars($document['description']); ?></textarea>

</div>

<div class="col-12 mb-3">

<label class="form-label">

Current File

</label>

<div class="form-control bg-light">

<?= htmlspecialchars($document['file_name']); ?>

</div>

</div>

<div class="col-12 mb-3">

<label class="form-label">

Replace File (Optional)

</label>

<input
type="file"
name="document"
class="form-control">

<div class="form-text">

Leave this empty if you don't want to replace the current file.

<br>

Allowed: PDF, DOC, DOCX, XLS, XLSX, ZIP, JPG, JPEG, PNG

<br>

Maximum Size: 10 MB

</div>

</div>

</div>

<button
type="submit"
name="update"
class="btn btn-primary">

<i class="bi bi-check-circle"></i>

Update Document

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