<?php

require_once("../config/auth.php");
requireRole("admin");

require_once("../config/database.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: departments.php");
    exit;
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("
SELECT *
FROM departments
WHERE id=?
");

$stmt->execute([$id]);

$department = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$department) {
    header("Location: departments.php");
    exit;
}

$message = "";

if (isset($_POST['update'])) {

    $department_name = trim($_POST['department_name']);
    $description     = trim($_POST['description']);
    $status          = $_POST['status'];

    if ($department_name == "") {

        $message = "<div class='alert alert-danger'>
                        Department Name is required.
                    </div>";

    } else {

        $check = $pdo->prepare("
        SELECT id
        FROM departments
        WHERE department_name=?
        AND id<>?
        ");

        $check->execute([
            $department_name,
            $id
        ]);

        if ($check->rowCount() > 0) {

            $message = "<div class='alert alert-danger'>
                            Department already exists.
                        </div>";

        } else {

            $update = $pdo->prepare("
            UPDATE departments
            SET
                department_name=?,
                description=?,
                status=?
            WHERE id=?
            ");

            $update->execute([
                $department_name,
                $description,
                $status,
                $id
            ]);

            header("Location: departments.php?updated=1");
            exit;
        }
    }
}

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<div class="content">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>Edit Department</h2>

<a href="departments.php" class="btn btn-secondary">

← Back

</a>

</div>

<?php
if($message!=""){
    echo $message;
}
?>

<div class="card shadow">

<div class="card-body">

<form method="post">

<div class="mb-3">

<label class="form-label">

Department Name

</label>

<input
type="text"
name="department_name"
class="form-control"
value="<?= htmlspecialchars($department['department_name']); ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">

Description

</label>

<textarea
name="description"
class="form-control"
rows="4"><?= htmlspecialchars($department['description']); ?></textarea>

</div>

<div class="mb-3">

<label class="form-label">

Status

</label>

<select
name="status"
class="form-select">

<option value="active"
<?= ($department['status']=="active") ? "selected" : ""; ?>>

Active

</option>

<option value="disabled"
<?= ($department['status']=="disabled") ? "selected" : ""; ?>>

Disabled

</option>

</select>

</div>

<button
type="submit"
name="update"
class="btn btn-primary">

Update Department

</button>

<a
href="departments.php"
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