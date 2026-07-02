<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

require_once("../config/database.php");

$message = "";

if(isset($_POST['save']))
{
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $created_by  = $_SESSION['id'];

    if($title=="" || $description=="")
    {
        $message = "
        <div class='alert alert-danger'>
            Please fill all required fields.
        </div>";
    }
    else
    {
        $stmt = $pdo->prepare("
            INSERT INTO announcements
            (
                title,
                description,
                created_by
            )
            VALUES
            (
                ?,?,?
            )
        ");

        $stmt->execute([
            $title,
            $description,
            $created_by
        ]);

        header("Location: announcements.php?added=1");
        exit;
    }
}

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<div class="content">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>Add Announcement</h2>

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
required>

</div>

<div class="mb-3">

<label class="form-label">

Announcement

</label>

<textarea
name="description"
class="form-control"
rows="6"
required></textarea>

</div>

<button
type="submit"
name="save"
class="btn btn-primary">

Save Announcement

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