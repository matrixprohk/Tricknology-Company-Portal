<?php

require_once("../config/auth.php");
requireRole("admin");

require_once("../config/database.php");

$message = "";

/*
|--------------------------------------------------------------------------
| Load Company Settings
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("SELECT * FROM settings LIMIT 1");
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Create Default Row (If Empty)
|--------------------------------------------------------------------------
*/

if (!$settings) {

    $pdo->exec("
        INSERT INTO settings
        (
            company_name,
            company_email,
            company_phone,
            company_address,
            company_website,
            company_logo
        )
        VALUES
        (
            '',
            '',
            '',
            '',
            '',
            ''
        )
    ");

    $stmt = $pdo->query("SELECT * FROM settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
}

/*
|--------------------------------------------------------------------------
| Save Settings
|--------------------------------------------------------------------------
*/

if (isset($_POST['update'])) {

    $company_name    = trim($_POST['company_name']);
    $company_email   = trim($_POST['company_email']);
    $company_phone   = trim($_POST['company_phone']);
    $company_address = trim($_POST['company_address']);
    $company_website = trim($_POST['company_website']);

    $logo = $settings['company_logo'];

    /*
    |--------------------------------------------------------------------------
    | Upload Logo
    |--------------------------------------------------------------------------
    */

    if (
        isset($_FILES['company_logo']) &&
        $_FILES['company_logo']['error'] == 0
    ) {

        $extension = strtolower(
            pathinfo(
                $_FILES['company_logo']['name'],
                PATHINFO_EXTENSION
            )
        );

        $allowed = ['png', 'jpg', 'jpeg'];

        if (in_array($extension, $allowed)) {

            if (
                !empty($logo) &&
                file_exists("../assets/images/" . $logo)
            ) {
                unlink("../assets/images/" . $logo);
            }

            $logo = time() . "_" . basename($_FILES['company_logo']['name']);

            move_uploaded_file(
                $_FILES['company_logo']['tmp_name'],
                "../assets/images/" . $logo
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Update Database
    |--------------------------------------------------------------------------
    */

    $update = $pdo->prepare("
        UPDATE settings SET

            company_name=?,
            company_email=?,
            company_phone=?,
            company_address=?,
            company_website=?,
            company_logo=?

        WHERE id=?
    ");

    $update->execute([

        $company_name,
        $company_email,
        $company_phone,
        $company_address,
        $company_website,
        $logo,
        $settings['id']

    ]);

    header("Location: settings.php?updated=1");
    exit;
}

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<div class="container-fluid">

<div class="content">

<h2 class="mb-4">

<i class="bi bi-gear-fill"></i>

Portal Settings

</h2>

<?php if(isset($_GET['updated'])){ ?>

<div class="alert alert-success">

Settings updated successfully.

</div>

<?php } ?>

<div class="card shadow">

<div class="card-header bg-dark text-white">

<h5 class="mb-0">

Company Information

</h5>

</div>

<div class="card-body">

<form
method="POST"
enctype="multipart/form-data">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Company Name

</label>

<input
type="text"
name="company_name"
class="form-control"
required
value="<?= htmlspecialchars($settings['company_name']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Company Email

</label>

<input
type="email"
name="company_email"
class="form-control"
value="<?= htmlspecialchars($settings['company_email']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Company Phone

</label>

<input
type="text"
name="company_phone"
class="form-control"
value="<?= htmlspecialchars($settings['company_phone']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Company Website

</label>

<input
type="text"
name="company_website"
class="form-control"
placeholder="https://example.com"
value="<?= htmlspecialchars($settings['company_website']); ?>">

</div>

<div class="col-12 mb-3">

<label class="form-label">

Company Address

</label>

<textarea
name="company_address"
class="form-control"
rows="4"><?= htmlspecialchars($settings['company_address']); ?></textarea>

</div>

<hr class="my-4">

<div class="col-md-6 mb-4">

<label class="form-label">

Current Company Logo

</label>

<br>

<?php if(!empty($settings['company_logo']) && file_exists("../assets/images/".$settings['company_logo'])){ ?>

<img
src="../assets/images/<?= htmlspecialchars($settings['company_logo']); ?>"
class="img-thumbnail"
style="max-height:120px;">

<?php } else { ?>

<div class="alert alert-secondary mb-0">

No logo uploaded.

</div>

<?php } ?>

</div>

<div class="col-md-6 mb-4">

<label class="form-label">

Upload New Logo

</label>

<input
type="file"
name="company_logo"
class="form-control"
accept=".png,.jpg,.jpeg">

<div class="form-text">

Supported formats:
PNG, JPG and JPEG

</div>

</div>

</div>

<hr>

<div class="d-flex justify-content-between flex-wrap">

<div>

<a
href="backup-database.php"
class="btn btn-success">

<i class="bi bi-download"></i>

Backup Database

</a>

<a
href="restore-database.php"
class="btn btn-warning">

<i class="bi bi-arrow-clockwise"></i>

Restore Database

</a>

</div>

<div>

<button
type="submit"
name="update"
class="btn btn-primary">

<i class="bi bi-check-circle"></i>

Save Settings

</button>

<a
href="dashboard.php"
class="btn btn-secondary">

<i class="bi bi-x-circle"></i>

Cancel

</a>

</div>

</div>

</form>

</div>

</div>

</div>

</div>

<?php
include("../includes/footer.php");
?>