<?php

require_once("../config/auth.php");
requireRole("admin");

require_once("../config/database.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: users.php");
    exit;
}

$id = (int)$_GET['id'];

$message = "";

/*
|--------------------------------------------------------------------------
| Load User
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT *
FROM users
WHERE id=?
");

$stmt->execute([$id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: users.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Update User
|--------------------------------------------------------------------------
*/

if(isset($_POST['save']))
{

    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $role     = $_POST['role'];
    $status   = $_POST['status'];

    if($fullname=="" || $username=="" || $email=="")
    {

        $message='
        <div class="alert alert-danger">
            Please fill all required fields.
        </div>';

    }
    else
    {

        /*
        ---------------------------------------
        Check Duplicate Username / Email
        ---------------------------------------
        */

        $check=$pdo->prepare("
        SELECT id
        FROM users
        WHERE
        (username=? OR email=?)
        AND id<>?
        ");

        $check->execute([
            $username,
            $email,
            $id
        ]);

        if($check->rowCount()>0)
        {

            $message='
            <div class="alert alert-danger">
                Username or Email already exists.
            </div>';

        }
        else
        {

            if(!empty($_POST['password']))
            {

                $password=password_hash(
                    $_POST['password'],
                    PASSWORD_DEFAULT
                );

                $update=$pdo->prepare("
                UPDATE users
                SET
                    fullname=?,
                    username=?,
                    email=?,
                    password=?,
                    role=?,
                    status=?
                WHERE id=?
                ");

                $update->execute([
                    $fullname,
                    $username,
                    $email,
                    $password,
                    $role,
                    $status,
                    $id
                ]);

            }
            else
            {

                $update=$pdo->prepare("
                UPDATE users
                SET
                    fullname=?,
                    username=?,
                    email=?,
                    role=?,
                    status=?
                WHERE id=?
                ");

                $update->execute([
                    $fullname,
                    $username,
                    $email,
                    $role,
                    $status,
                    $id
                ]);

            }

            /*
            ---------------------------------------
            Activity Log
            ---------------------------------------
            */

            $log=$pdo->prepare("
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
                "Updated user : ".$fullname,
                $_SERVER['REMOTE_ADDR']
            ]);

            header("Location: users.php?updated=1");
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

<i class="bi bi-person-gear"></i>

Edit User

</h2>

<p class="text-muted mb-0">

Update user information

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
required
value="<?= htmlspecialchars($user['fullname']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Username

</label>

<input
type="text"
name="username"
class="form-control"
required
value="<?= htmlspecialchars($user['username']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Email

</label>

<input
type="email"
name="email"
class="form-control"
required
value="<?= htmlspecialchars($user['email']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

New Password

</label>

<input
type="password"
name="password"
class="form-control"
placeholder="Leave blank to keep current password">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Role

</label>

<select
name="role"
class="form-select">

<option
value="admin"
<?= ($user['role']=="admin") ? "selected" : ""; ?>>

Administrator

</option>

<option
value="user"
<?= ($user['role']=="user") ? "selected" : ""; ?>>

User

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

<option
value="active"
<?= ($user['status']=="active") ? "selected" : ""; ?>>

Active

</option>

<option
value="disabled"
<?= ($user['status']=="disabled") ? "selected" : ""; ?>>

Disabled

</option>

</select>

</div>

</div>

<hr>

<button
type="submit"
name="save"
class="btn btn-primary">

<i class="bi bi-check-circle"></i>

Update User

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