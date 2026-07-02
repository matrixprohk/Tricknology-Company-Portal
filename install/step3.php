<?php

session_start();

if (!isset($_SESSION['db_host'])) {
    header("Location: step1.php");
    exit;
}

$pdo = new PDO(
    "mysql:host=".$_SESSION['db_host'].
    ";dbname=".$_SESSION['db_name'].
    ";charset=utf8mb4",

    $_SESSION['db_user'],
    $_SESSION['db_pass']
);

$message="";

if(isset($_POST['install']))
{

    $company_name = trim($_POST['company_name']);

    $fullname = trim($_POST['fullname']);

    $username = trim($_POST['username']);

    $email = trim($_POST['email']);

    $password = $_POST['password'];

    $confirm = $_POST['confirm'];

    if($password != $confirm)
    {
        $message="<div class='error'>Passwords do not match.</div>";
    }
    else
    {

        $hash = password_hash($password,PASSWORD_DEFAULT);

        /*
        -------------------------------------
        Update Company Settings
        -------------------------------------
        */

        $stmt=$pdo->prepare("
        UPDATE settings
        SET company_name=?
        WHERE id=1
        ");

        $stmt->execute([$company_name]);

        /*
        -------------------------------------
        Create Administrator
        -------------------------------------
        */

        $stmt=$pdo->prepare("
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
            ?,?,?,?,
            'admin',
            'active'
        )
        ");

        $stmt->execute([

            $fullname,

            $username,

            $email,

            $hash

        ]);

        header("Location: step4.php");

        exit;

    }

}

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>

Administrator Setup

</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="installer-box">

<h1>

Step 3

</h1>

<h2>

Administrator Account

</h2>

<?= $message ?>

<form method="post">

<label>

Company Name

</label>

<input
type="text"
name="company_name"
required>

<label>

Administrator Name

</label>

<input
type="text"
name="fullname"
required>

<label>

Username

</label>

<input
type="text"
name="username"
required>

<label>

Email

</label>

<input
type="email"
name="email"
required>

<label>

Password

</label>

<input
type="password"
name="password"
required>

<label>

Confirm Password

</label>

<input
type="password"
name="confirm"
required>

<br><br>

<button
class="btn"
name="install">

Install Portal →

</button>

</form>

</div>

</body>

</html>