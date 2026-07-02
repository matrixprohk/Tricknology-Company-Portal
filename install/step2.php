<?php

session_start();

if (
    !isset($_SESSION['db_host']) ||
    !isset($_SESSION['db_name']) ||
    !isset($_SESSION['db_user'])
) {
    header("Location: step1.php");
    exit;
}

$message = "";
$success = false;

try {

    $pdo = new PDO(
        "mysql:host=" . $_SESSION['db_host'] .
        ";dbname=" . $_SESSION['db_name'] .
        ";charset=utf8mb4",
        $_SESSION['db_user'],
        $_SESSION['db_pass']
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /*
    -----------------------------------------
    Check Database is Empty
    -----------------------------------------
    */

    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_ASSOC);

    if (count($tables) > 0) {

        $message = "
        <div class='error'>
            <b>Database is not empty.</b><br><br>
            Please create a new empty database before continuing.
        </div>";

    } else {

        /*
        -----------------------------------------
        Load SQL File
        -----------------------------------------
        */

        $sql = file_get_contents(__DIR__ . "/database.sql");

        if ($sql === false) {
            throw new Exception("database.sql file not found.");
        }

        /*
        -----------------------------------------
        Import Database
        -----------------------------------------
        */

        $pdo->exec($sql);

        $success = true;

        $message = "
        <div class='success'>
            ✔ Database imported successfully.
        </div>";

    }

}
catch(Exception $e){

    $message = "
    <div class='error'>
        <b>Database Import Failed</b><br><br>
        ".$e->getMessage()."
    </div>";

}

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Database Installation</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="installer-box">

<h1>Step 2</h1>

<h2>Import Database</h2>

<br>

<?= $message ?>

<?php if($success){ ?>

<a href="step3.php" class="btn">

Continue →

</a>

<?php } ?>

</div>

</body>

</html>