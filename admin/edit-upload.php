<?php

require_once("../config/auth.php");
requireRole("admin");

require_once("../config/database.php");

if (!isset($_GET['id'])) {
    header("Location: upload-center.php");
    exit;
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM uploads WHERE id=?");
$stmt->execute([$id]);
$file = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$file) {
    header("Location: upload-center.php");
    exit;
}

$message = "";

if (isset($_POST['update'])) {

    $title = trim($_POST['title']);
    $category = trim($_POST['category']);

    $filename = $file['filename'];

    if (!empty($_FILES['file']['name'])) {

        $allowed = [
            "pdf","doc","docx","xls","xlsx",
            "ppt","pptx","txt","jpg","jpeg",
            "png","gif","zip","rar"
        ];

        $extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

        if (in_array($extension, $allowed)) {

            if (file_exists("../uploads/".$filename)) {
                unlink("../uploads/".$filename);
            }

            $newName = time()."_".preg_replace(
                "/[^A-Za-z0-9._-]/",
                "_",
                $_FILES['file']['name']
            );

            move_uploaded_file(
                $_FILES['file']['tmp_name'],
                "../uploads/".$newName
            );

            $filename = $newName;

        } else {

            $message = '<div class="alert alert-danger">
            Invalid file type.
            </div>';

        }

    }

    if ($message=="") {

        $stmt = $pdo->prepare("
        UPDATE uploads
        SET
            title=?,
            category=?,
            filename=?
        WHERE id=?
        ");

        $stmt->execute([
            $title,
            $category,
            $filename,
            $id
        ]);

        header("Location: upload-center.php?updated");
        exit;

    }

}

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<div class="content">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>

<i class="bi bi-pencil-square"></i>

Edit Upload

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

Title

</label>

<input
type="text"
name="title"
class="form-control"
required
value="<?= htmlspecialchars($file['title']); ?>">

</div>

<div class="mb-3">

<label class="form-label">

Category

</label>

<select
name="category"
class="form-select"
required>

<?php

$categories=[
"HR",
"Finance",
"IT",
"Marketing",
"Sales",
"Administration",
"Policy",
"Others"
];

foreach($categories as $cat){

?>

<option
value="<?= $cat; ?>"
<?= $file['category']==$cat?'selected':''; ?>>

<?= $cat; ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label class="form-label">

Current File

</label>

<input
type="text"
class="form-control"
value="<?= htmlspecialchars($file['filename']); ?>"
readonly>

</div>

<div class="mb-3">

<label class="form-label">

Replace File (Optional)

</label>

<input
type="file"
name="file"
class="form-control">

<small class="text-muted">

Leave blank to keep existing file.

</small>

</div>

<div class="mt-4">

<button
type="submit"
name="update"
class="btn btn-primary">

Update File

</button>

<a
href="upload-center.php"
class="btn btn-danger">

Cancel

</a>

</div>

</form>

</div>

</div>

</div>

<?php
include("../includes/footer.php");
?>