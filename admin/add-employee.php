<?php

require_once("../config/auth.php");
requireRole("admin");

require_once("../config/database.php");

$message = "";

/*
|--------------------------------------------------------------------------
| Load Departments
|--------------------------------------------------------------------------
*/

$dept = $pdo->query("
SELECT *
FROM departments
ORDER BY department_name ASC
");

$departments = $dept->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Save Employee
|--------------------------------------------------------------------------
*/

if(isset($_POST['save']))
{

    $employee_code = trim($_POST['employee_code']);
    $fullname      = trim($_POST['fullname']);
    $email         = trim($_POST['email']);
    $phone         = trim($_POST['phone']);
    $department_id = !empty($_POST['department_id'])
    ? (int)$_POST['department_id']
    : null;
    $designation   = trim($_POST['designation']);
    $address       = trim($_POST['address']);
    $joining_date  = $_POST['joining_date'];
    $status        = $_POST['status'];

    if (
    $employee_code == "" ||
    $fullname == "" ||
    $email == "" ||
    $department_id === null
)
{

    $message = "
    <div class='alert alert-danger'>
        Please fill all required fields including Department.
    </div>";

    }
    else
    {

        $check=$pdo->prepare("
        SELECT id
        FROM employees
        WHERE employee_code=?
        ");

        $check->execute([$employee_code]);

        if($check->rowCount()>0)
        {

            $message="
            <div class='alert alert-danger'>
                Employee Code already exists.
            </div>";

        }
        else
        {

            $insert=$pdo->prepare("
            INSERT INTO employees
            (
                employee_code,
                fullname,
                email,
                phone,
                department_id,
                designation,
                address,
                joining_date,
                status
            )
            VALUES
            (
                ?,?,?,?,?,?,?,?,?
            )
            ");

            $insert->execute([

                $employee_code,
                $fullname,
                $email,
                $phone,
                $department_id,
                $designation,
                $address,
                $joining_date,
                $status

            ]);

            header("Location: employees.php?added=1");
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

<h2>Add Employee</h2>

<a href="employees.php" class="btn btn-secondary">

← Back

</a>

</div>

<?= $message ?>

<div class="card shadow">

<div class="card-body">

<form method="post">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Employee Code

</label>

<input
type="text"
name="employee_code"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Full Name

</label>

<input
type="text"
name="fullname"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Email

</label>

<input
type="email"
name="email"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Phone

</label>

<input
type="text"
name="phone"
class="form-control">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Department

</label>

<select
name="department_id"
class="form-select"
required>

<option value="">-- Select Department --</option>

<?php foreach($departments as $row){ ?>

<option value="<?= $row['id']; ?>">

<?= htmlspecialchars($row['department_name']); ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Designation

</label>

<input
type="text"
name="designation"
class="form-control">

</div>

<div class="col-12 mb-3">

<label class="form-label">

Address

</label>

<textarea
name="address"
class="form-control"
rows="3"></textarea>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Joining Date

</label>

<input
type="date"
name="joining_date"
class="form-control">

</div>

<div class="col-md-6 mb-3">

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

</div>

<button
type="submit"
name="save"
class="btn btn-primary">

Save Employee

</button>

<a
href="employees.php"
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