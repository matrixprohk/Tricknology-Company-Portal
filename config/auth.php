<?php
// ==========================================
// Company Portal Authentication
// config/auth.php
// ==========================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Check Login
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['id'])) {

    header("Location: ../login.php");
    exit;

}

/*
|--------------------------------------------------------------------------
| Logged In User Information
|--------------------------------------------------------------------------
*/

$currentUserId   = $_SESSION['id'];
$currentUserName = $_SESSION['fullname'] ?? "";
$currentUserRole = strtolower($_SESSION['role'] ?? "user");

/*
|--------------------------------------------------------------------------
| Role Check Function
|--------------------------------------------------------------------------
*/

function requireRole($roles)
{
    global $currentUserRole;

    if (!is_array($roles)) {
        $roles = [$roles];
    }

    $roles = array_map('strtolower', $roles);

    if (!in_array($currentUserRole, $roles)) {

        http_response_code(403);
?>
<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Access Denied</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

body{

background:#f5f7fb;

font-family:Segoe UI;

display:flex;

justify-content:center;

align-items:center;

height:100vh;

}

.access-box{

width:500px;

background:#fff;

padding:40px;

border-radius:12px;

box-shadow:0 10px 30px rgba(0,0,0,.15);

text-align:center;

}

.access-box h1{

font-size:80px;

margin:0;

color:#dc3545;

}

.access-box h2{

margin:10px 0;

}

.access-box p{

color:#666;

margin-bottom:30px;

}

.btn{

display:inline-block;

padding:12px 25px;

background:#0d6efd;

color:#fff;

text-decoration:none;

border-radius:6px;

}

.btn:hover{

background:#0b5ed7;

}

</style>

</head>

<body>

<div class="access-box">

<h1>🚫</h1>

<h2>Access Denied</h2>

<p>

Sorry, you don't have permission to access this page.

</p>

<a href="dashboard.php" class="btn">

Go Back

</a>

</div>

</body>

</html>

<?php
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
*/

function isAdmin()
{
    return strtolower($_SESSION['role']) === "admin";
}

function isUser()
{
    return strtolower($_SESSION['role']) === "user";
}

?>