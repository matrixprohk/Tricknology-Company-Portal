<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

require_once("../config/database.php");

/*
|--------------------------------------------------------------------------
| Load Activity Logs
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
SELECT
    activity_logs.*,
    users.fullname
FROM activity_logs
LEFT JOIN users
ON activity_logs.user_id = users.id
ORDER BY activity_logs.id DESC
");

$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/sidebar.php");
?>

<div class="content">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>

<i class="bi bi-clock-history"></i>

Activity Logs

</h2>

</div>

<div class="card shadow">

<div class="card-header">

<h5 class="mb-0">

System Activity Logs

</h5>

</div>

<div class="card-body">

<table class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th width="70">ID</th>

<th>User</th>

<th>Activity</th>

<th>IP Address</th>

<th width="180">Date & Time</th>

</tr>

</thead>

<tbody>

<?php if(count($logs)>0){ ?>

<?php foreach($logs as $row){ ?>

<tr>

<td>

<?= $row['id']; ?>

</td>

<td>

<?= htmlspecialchars($row['fullname'] ?? 'Unknown User'); ?>

</td>

<td>

<?= htmlspecialchars($row['activity']); ?>

</td>

<td>

<?= htmlspecialchars($row['ip_address']); ?>

</td>

<td>

<?= date("d M Y h:i A", strtotime($row['created_at'])); ?>

</td>

</tr>

<?php } ?>

<?php } else { ?>

<tr>

<td colspan="5" class="text-center">

No activity logs found.

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<?php
include("../includes/footer.php");
?>