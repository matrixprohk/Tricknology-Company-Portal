<?php

require_once("../config/auth.php");
requireRole("admin");

require_once("../config/database.php");

if (!isset($_GET['id'])) {
    header("Location: announcements.php");
    exit;
}

$id = (int)$_GET['id'];

/*
|--------------------------------------------------------------------------
| Load Announcement
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT *
FROM announcements
WHERE id=?
");

$stmt->execute([$id]);

$announcement = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$announcement) {
    header("Location: announcements.php");
    exit;
}

$message = "";

/*
|--------------------------------------------------------------------------
| Update Announcement
|--------------------------------------------------------------------------
*/

if(isset($_POST['update']))
{

    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);

    if($title=="" || $description=="")
    {

        $message = "
        <div class='alert alert-danger'>
            Please fill all required fields.
        </div>";

    }
    else
    {

        $update = $pdo->prepare("
        UPDATE announcements
        SET
            title=?,
            description=?
        WHERE id=?
        ");

        $update->execute([
            $title,
            $description,
            $id
        ]);

        header("Location: announcements.php?updated=1");
        exit;

    }

}

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<div class="content">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>Edit Announcement</h2>

<a href="announcements.php" class="btn btn-secondary">

← Back

</a>

</div>

<?= $message ?>

<div class="card shadow">

<div class="card-body">

<form method="post">

<div class="mb-3">

<label class="form-label">

Title

</label>

<input
type="text"
name="title"
class="form-control"
value="<?= htmlspecialchars($announcement['title']); ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">

Description

</label>

<textarea
name="description"
class="form-control"
rows="6"
required><?= htmlspecialchars($announcement['description']); ?></textarea>

</div>

<button
type="submit"
name="update"
class="btn btn-primary">

<i class="bi bi-check-circle"></i>

Update Announcement

</button>

<a
href="announcements.php"
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