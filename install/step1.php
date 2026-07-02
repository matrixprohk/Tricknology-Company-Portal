<?php
session_start();

$message = "";

if (isset($_POST['test'])) {

    $host = trim($_POST['host']);
    $database = trim($_POST['database']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {

        $pdo = new PDO(
            "mysql:host=$host;dbname=$database;charset=utf8mb4",
            $username,
            $password
        );

        $_SESSION['db_host'] = $host;
        $_SESSION['db_name'] = $database;
        $_SESSION['db_user'] = $username;
        $_SESSION['db_pass'] = $password;

        $message = "<div class='success'>
                        ✔ Database connection successful.
                    </div>";

    } catch (PDOException $e) {

        $message = "<div class='error'>
                        ❌ Connection failed.<br>
                        " . $e->getMessage() . "
                    </div>";

    }

}
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Database Configuration</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="installer-box">

<h1>Database Configuration</h1>

<p>

Enter your MySQL database information.

</p>

<hr>

<?= $message ?>

<form method="post">

<label>Database Host</label>

<input
type="text"
name="host"
value="localhost"
required>

<label>Database Name</label>

<input
type="text"
name="database"
value="companyportal"
required>

<label>Username</label>

<input
type="text"
name="username"
value="root"
required>

<label>Password</label>

<input
type="password"
name="password">

<br>

<button
type="submit"
name="test"
class="btn">

Test Connection

</button>

<?php if(isset($_SESSION['db_host'])){ ?>

<a href="step2.php" class="btn">

Next →

</a>

<?php } ?>

</form>

</div>

</body>

</html>