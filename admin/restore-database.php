<?php
/*
|--------------------------------------------------------------------------
| Tricknology Company Portal
|--------------------------------------------------------------------------
| File        : restore-database.php
| Author      : Tapan Hazra
| Channel     : Tricknology
|--------------------------------------------------------------------------
*/

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

include("../config/database.php");

$message = "";

if(isset($_POST['restore']))
{

    if(isset($_FILES['backup_file']) &&
       $_FILES['backup_file']['error']==0)
    {

        $extension = strtolower(pathinfo(
            $_FILES['backup_file']['name'],
            PATHINFO_EXTENSION
        ));

        if($extension!="sql")
        {

            $message="
            <div class='alert alert-danger'>
                Please select a valid SQL backup file.
            </div>";

        }
        else
        {

            $restoreFolder="../backup/";

            if(!is_dir($restoreFolder))
            {
                mkdir($restoreFolder,0777,true);
            }

            $fileName=time()."_".$_FILES['backup_file']['name'];

            $filePath=$restoreFolder.$fileName;

            if(move_uploaded_file(
                $_FILES['backup_file']['tmp_name'],
                $filePath
            ))
            {

                /*
                --------------------------------------------------------------
                Database Information
                --------------------------------------------------------------
                */

                $database="companyportal";
                $username="root";
                $password="dsf322";

                /*
                --------------------------------------------------------------
                MySQL Command
                --------------------------------------------------------------
                */

                $command =
                'mysql --user=' .
                escapeshellarg($username) .
                ' --password=' .
                escapeshellarg($password) .
                ' ' .
                escapeshellarg($database) .
                ' < ' .
                escapeshellarg($filePath);

                exec($command,$output,$result);

                if($result==0)
                {

                    /*
                    ----------------------------------------------------------
                    Activity Log
                    ----------------------------------------------------------
                    */

                    $log=$pdo->prepare("
                    INSERT INTO activity_logs
                    (
                        user_name,
                        activity,
                        ip_address
                    )
                    VALUES
                    (
                        ?,?,?
                    )
                    ");

                    $log->execute([

                        $_SESSION['fullname'] ?? 'Administrator',

                        'Restored Database Backup',

                        $_SERVER['REMOTE_ADDR']

                    ]);

                    $message="
                    <div class='alert alert-success'>
                        Database Restored Successfully.
                    </div>";

                }
                else
                {

                    $message="
                    <div class='alert alert-danger'>
                        Database Restore Failed.
                    </div>";

                }

            }
            else
            {

                $message="
                <div class='alert alert-danger'>
                    Unable to upload backup file.
                </div>";

            }

        }

    }
    else
    {

        $message="
        <div class='alert alert-danger'>
            Please choose a backup file.
        </div>";

    }

}

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<h2 class="mb-4">

Restore Database

</h2>

<?php
echo $message;
?>

<div class="card shadow">

<div class="card-header">

<h5>

Restore SQL Backup

</h5>

</div>

<div class="card-body">

<form
method="post"
enctype="multipart/form-data">

<div class="mb-3">

<label class="form-label">

SQL Backup File

</label>

<input
type="file"
name="backup_file"
class="form-control"
accept=".sql"
required>

<div class="form-text">

Only .sql backup files are allowed.

</div>

</div>

<button
type="submit"
name="restore"
class="btn btn-warning">

<i class="bi bi-arrow-clockwise"></i>

Restore Database

</button>

<a
href="settings.php"
class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

<?php
include("../includes/footer.php");
?>