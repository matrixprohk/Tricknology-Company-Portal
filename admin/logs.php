<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

include("../config/database.php");

$stmt = $pdo->prepare("
    SELECT *
    FROM activity_logs
    ORDER BY id DESC
");

$stmt->execute();

$logs = $stmt->fetchAll();

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<h2 class="mb-4">Activity Logs</h2>

<div class="card shadow">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">

            System Activity Logs

        </h5>

        <a href="clear-logs.php"
           class="btn btn-danger"
           onclick="return confirm('Are you sure you want to clear all logs?');">

            <i class="bi bi-trash"></i>

            Clear Logs

        </a>

    </div>

    <div class="card-body">

        <?php if(isset($_GET['cleared'])) { ?>

            <div class="alert alert-success">

                Activity Logs Cleared Successfully.

            </div>

        <?php } ?>

        <table class="table table-bordered table-hover align-middle">

            <thead class="table-dark">

                <tr>

                    <th width="70">ID</th>

                    <th>User</th>

                    <th>Activity</th>

                    <th width="150">IP Address</th>

                    <th width="180">Date & Time</th>

                </tr>

            </thead>

            <tbody>

            <?php if(count($logs)>0){ ?>

                <?php foreach($logs as $row){ ?>

                <tr>

                    <td><?= $row['id']; ?></td>

                    <td><?= htmlspecialchars($row['user_name']); ?></td>

                    <td><?= htmlspecialchars($row['activity']); ?></td>

                    <td><?= htmlspecialchars($row['ip_address']); ?></td>

                    <td><?= date("d M Y h:i A", strtotime($row['created_at'])); ?></td>

                </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>

                    <td colspan="5" class="text-center">

                        No Activity Logs Found

                    </td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<?php
include("../includes/footer.php");
?>