<?php

require_once("../config/auth.php");
requireRole("admin");

require_once("../config/database.php");

$message = "";

if (isset($_POST['save'])) {

    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $role   = $_POST['role'];
    $status = $_POST['status'];

    if (
        $fullname == "" ||
        $username == "" ||
        $email == "" ||
        $_POST['password'] == ""
    ) {

        $message = '
        <div class="alert alert-danger">
            Please fill all required fields.
        </div>';

    } else {

        /*
        --------------------------------------------------------
        Check Duplicate Username / Email
        --------------------------------------------------------
        */

        $check = $pdo->prepare("
            SELECT id
            FROM users
            WHERE username=?
               OR email=?
        ");

        $check->execute([
            $username,
            $email
        ]);

        if ($check->rowCount() > 0) {

            $message = '
            <div class="alert alert-danger">
                Username or Email already exists.
            </div>';

        } else {

            $stmt = $pdo->prepare("
                INSERT INTO users
                (
                    fullname,
                    username,
                    email,
                    password,
                    role,
                    status
                )
                VALUES
                (
                    ?,?,?,?,?,?
                )
            ");

            $stmt->execute([
                $fullname,
                $username,
                $email,
                $password,
                $role,
                $status
            ]);

            /*
            --------------------------------------------------------
            Activity Log
            --------------------------------------------------------
            */

            $log = $pdo->prepare("
                INSERT INTO activity_logs
                (
                    user_id,
                    activity,
                    ip_address
                )
                VALUES
                (
                    ?,?,?
                )
            ");

            $log->execute([
                $_SESSION['id'],
                "Created user : ".$fullname,
                $_SERVER['REMOTE_ADDR']
            ]);

            header("Location: users.php?added=1");
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

<div>

<h2>

<i class="bi bi-person-plus-fill"></i>

Add User

</h2>

<p class="text-muted mb-0">

Create a new portal user

</p>

</div>

<a href="users.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

<?= $message ?>

<div class="card shadow border-0">

<div class="card-header bg-dark text-white">

<h5 class="mb-0">

User Information

</h5>

</div>

<div class="card-body">

<form method="POST">

<div class="row">

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

Username

</label>

<input
type="text"
name="username"
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

Password

</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Role

</label>

<select
name="role"
class="form-select">

<option value="user">

User

</option>

<option value="admin">

Administrator

</option>

</select>

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

<i class="bi bi-check-circle"></i>

Create User

</button>

<a
href="users.php"
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