<?php
session_start();

if(isset($_SESSION['id']))
{
    header("Location: admin/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Company Portal</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{

font-family:Segoe UI,Arial,sans-serif;

background:linear-gradient(135deg,#0f172a,#1e3a8a);

height:100vh;

overflow:hidden;

color:white;

}

.navbar{

background:rgba(255,255,255,.05);

backdrop-filter:blur(12px);

padding:18px 40px;

}

.hero{

height:calc(100vh - 75px);

display:flex;

align-items:center;

justify-content:center;

}

.hero-content{

text-align:center;

max-width:900px;

}

.hero h1{

font-size:58px;

font-weight:700;

margin-bottom:20px;

}

.hero p{

font-size:22px;

color:#dbeafe;

margin-bottom:40px;

}

.btn-login{

padding:14px 40px;

font-size:20px;

border-radius:50px;

}

.features{

margin-top:70px;

display:grid;

grid-template-columns:repeat(4,1fr);

gap:25px;

}

.feature{

background:rgba(255,255,255,.08);

padding:25px;

border-radius:15px;

transition:.3s;

}

.feature:hover{

transform:translateY(-8px);

background:rgba(255,255,255,.15);

}

.feature i{

font-size:45px;

margin-bottom:15px;

color:#60a5fa;

}

.footer{

position:absolute;

bottom:15px;

width:100%;

text-align:center;

font-size:14px;

color:#cbd5e1;

}

</style>

</head>

<body>

<nav class="navbar navbar-dark">

<div class="container-fluid">

<span class="navbar-brand mb-0 h1">

<i class="bi bi-buildings-fill"></i>

Company Portal

</span>

<a href="login.php" class="btn btn-outline-light">

<i class="bi bi-box-arrow-in-right"></i>

Login

</a>

</div>

</nav>

<div class="hero">

<div class="hero-content">

<h1>

Welcome to Company Portal

</h1>

<p>

A modern Employee Management & Document Management System

</p>

<a href="login.php" class="btn btn-primary btn-lg btn-login">

<i class="bi bi-box-arrow-in-right"></i>

Employee Login

</a>

<div class="features">

<div class="feature">

<i class="bi bi-people-fill"></i>

<h4>Employees</h4>

<p>

Manage employees efficiently.

</p>

</div>

<div class="feature">

<i class="bi bi-folder-fill"></i>

<h4>Documents</h4>

<p>

Secure document repository.

</p>

</div>

<div class="feature">

<i class="bi bi-cloud-upload-fill"></i>

<h4>Upload Center</h4>

<p>

Share files securely.

</p>

</div>

<div class="feature">

<i class="bi bi-shield-lock-fill"></i>

<h4>Secure Access</h4>

<p>

Role based authentication.

</p>

</div>

</div>

</div>

</div>

<div class="footer">

© <?php echo date("Y"); ?> Company Portal | Designed by Tricknology

</div>

</body>

</html>