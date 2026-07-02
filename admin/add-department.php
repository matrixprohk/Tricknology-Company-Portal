<?php

require_once("../config/auth.php");
requireRole("admin");

require_once("../config/database.php");

$message = "";

if (isset($_POST['save'])) {

    $department_name = trim($_POST['department_name']);
    $description     = trim($_POST['description']);
    $status          = $_POST['status'];

    if ($department_name == "") {

        $message = "<div class='alert alert-danger'>
                        Department Name is required.
                    </div>";

    } else {

        // Check duplicate department

        $check = $pdo->prepare("
            SELECT id
            FROM departments
            WHERE department_name=?
        ");

        $check->execute([$department_name]);

        if ($check->rowCount() > 0) {

            $message = "<div class='alert alert-danger'>
                            Department already exists.
                        </div>";

        } else {

            $stmt = $pdo->prepare("
                INSERT INTO departments
                (
                    department_name,
                    description,
                    status
                )
                VALUES
                (
                    ?,?,?
                )
            ");

            $stmt->execute([
                $department_name,
                $description,
                $status
            ]);

            header("Location: departments.php?added=1");
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

<h2>Add Department</h2>

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

Status

</label>

<select
name="status"
class="form-select">

<option value="active">

Active

</option>

<option value="disabled">

Disabled

</option>

</select>

</div>

<button
type="submit"
name="save"
class="btn btn-primary">

Save Department

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