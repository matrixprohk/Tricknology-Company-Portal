<?php
/*
|--------------------------------------------------------------------------
| Tricknology Company Portal
|--------------------------------------------------------------------------
| File        : company-profile.php
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

$stmt = $pdo->prepare("
    SELECT *
    FROM settings
    LIMIT 1
");

$stmt->execute();

$company = $stmt->fetch();

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<h2 class="mb-4">

Company Profile

</h2>

<div class="card shadow">

    <div class="card-body">

        <div class="row">

            <div class="col-md-3 text-center">

                <?php if(!empty($company['company_logo']) && file_exists("../assets/images/".$company['company_logo'])){ ?>

                    <img
                    src="../assets/images/<?php echo htmlspecialchars($company['company_logo']); ?>"
                    class="img-fluid rounded border p-2"
                    style="max-height:180px;">

                <?php } else { ?>

                    <img
                    src="../assets/images/logo.png"
                    class="img-fluid rounded border p-2"
                    style="max-height:180px;">

                <?php } ?>

            </div>

            <div class="col-md-9">

                <table class="table table-bordered">

                    <tr>

                        <th width="220">

                            Company Name

                        </th>

                        <td>

                            <?php echo htmlspecialchars($company['company_name']); ?>

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Email

                        </th>

                        <td>

                            <?php echo htmlspecialchars($company['company_email']); ?>

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Phone

                        </th>

                        <td>

                            <?php echo htmlspecialchars($company['company_phone']); ?>

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Website

                        </th>

                        <td>

                            <a
                            href="<?php echo htmlspecialchars($company['company_website']); ?>"
                            target="_blank">

                            <?php echo htmlspecialchars($company['company_website']); ?>

                            </a>

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Address

                        </th>

                        <td>

                            <?php echo nl2br(htmlspecialchars($company['company_address'])); ?>

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Last Updated

                        </th>

                        <td>

                            <?php echo date("d M Y h:i A", strtotime($company['updated_at'])); ?>

                        </td>

                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>

<div class="mt-3">

    <a
    href="settings.php"
    class="btn btn-primary">

    <i class="bi bi-pencil-square"></i>

    Edit Company Information

    </a>

</div>

<?php
include("../includes/footer.php");
?>