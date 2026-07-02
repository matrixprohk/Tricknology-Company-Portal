<?php
/*
|--------------------------------------------------------------------------
| Tricknology Company Portal
|--------------------------------------------------------------------------
| File        : change-password.php
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

if(isset($_POST['change']))
{
    $current_password = $_POST['current_password'];
    $new_password     = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if(
        empty($current_password) ||
        empty($new_password) ||
        empty($confirm_password)
    )
    {
        $message = "<div class='alert alert-danger'>
                        All fields are required.
                    </div>";
    }
    elseif($new_password != $confirm_password)
    {
        $message = "<div class='alert alert-danger'>
                        New Password and Confirm Password do not match.
                    </div>";
    }
    else
    {
        $stmt = $pdo->prepare("
            SELECT password
            FROM users
            WHERE id=?
        ");

        $stmt->execute([$_SESSION['id']]);

        $user = $stmt->fetch();

        if(!$user)
        {
            $message = "<div class='alert alert-danger'>
                            User not found.
                        </div>";
        }
        elseif(!password_verify($current_password,$user['password']))
        {
            $message = "<div class='alert alert-danger'>
                            Current Password is incorrect.
                        </div>";
        }
        else
        {
            $hash = password_hash(
                $new_password,
                PASSWORD_DEFAULT
            );

            $update = $pdo->prepare("
                UPDATE users
                SET password=?
                WHERE id=?
            ");

            $update->execute([
                $hash,
                $_SESSION['id']
            ]);

            // Activity Log (Optional)

            $log = $pdo->prepare("
                INSERT INTO activity_logs
                (
                    user_name,
                    activity,
                    ip_address
                )
                VALUES
                (
                    ?, ?, ?
                )
            ");

            $log->execute([
                $_SESSION['fullname'] ?? 'Administrator',
                'Changed account password',
                $_SERVER['REMOTE_ADDR']
            ]);

            $message = "<div class='alert alert-success'>
                            Password Changed Successfully.
                        </div>";
        }
    }
}

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<h2 class="mb-4">

Change Password

</h2>

<?php echo $message; ?>

<div class="card shadow">

    <div class="card-header">

        <h5>

            Update Account Password

        </h5>

    </div>

    <div class="card-body">

        <form method="post">

            <div class="mb-3">

                <label class="form-label">

                    Current Password

                </label>

                <input
                type="password"
                name="current_password"
                class="form-control"
                required>

            </div>

            <div class="mb-3">

                <label class="form-label">

                    New Password

                </label>

                <input
                type="password"
                name="new_password"
                class="form-control"
                required>

            </div>

            <div class="mb-3">

                <label class="form-label">

                    Confirm Password

                </label>

                <input
                type="password"
                name="confirm_password"
                class="form-control"
                required>

            </div>

            <button
            type="submit"
            name="change"
            class="btn btn-primary">

                <i class="bi bi-key"></i>

                Change Password

            </button>

            <a
            href="dashboard.php"
            class="btn btn-secondary">

                Cancel

            </a>

        </form>

    </div>

</div>

<?php
include("../includes/footer.php");
?>