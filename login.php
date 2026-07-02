<?php
session_start();

if (isset($_SESSION['id'])) {
    header("Location: admin/dashboard.php");
    exit;
}

require_once "config/database.php";

$message = "";

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username == "" || $password == "") {

        $message = '<div class="alert alert-danger">
                        Please enter Username and Password.
                    </div>';

    } else {

        $stmt = $pdo->prepare("
            SELECT *
            FROM users
            WHERE username=?
               OR email=?
            LIMIT 1
        ");

        $stmt->execute([$username, $username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            if (
                password_verify($password, $user['password']) ||
                $password == $user['password']
            ) {

                if (strtolower($user['status']) != "active") {

                    $message = '<div class="alert alert-danger">
                                    Your account is inactive.
                                </div>';

                } else {

                    $_SESSION['id']       = $user['id'];
                    $_SESSION['fullname'] = $user['fullname'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email']    = $user['email'];
                    $_SESSION['role']     = $user['role'];

                    header("Location: admin/dashboard.php");
                    exit;

                }

            } else {

                $message = '<div class="alert alert-danger">
                                Invalid password.
                            </div>';

            }

        } else {

            $message = '<div class="alert alert-danger">
                            Invalid username or email.
                        </div>';

        }

    }

}
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Company Portal Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/login.css">

</head>

<body>

<div class="background">

<div class="overlay"></div>

<div class="container-fluid h-100">

<div class="row h-100">

<div class="col-lg-6 d-none d-lg-flex left-panel">

<div class="welcome-box">

<h1>

<i class="bi bi-buildings-fill"></i>

Company Portal

</h1>

<p>

Employee Management System

</p>

<ul>

<li>

<i class="bi bi-check-circle-fill"></i>

Employee Management

</li>

<li>

<i class="bi bi-check-circle-fill"></i>

Department Management

</li>

<li>

<i class="bi bi-check-circle-fill"></i>

Document Management

</li>

<li>

<i class="bi bi-check-circle-fill"></i>

Secure File Upload

</li>

<li>

<i class="bi bi-check-circle-fill"></i>

Activity Logs

</li>

<li>

<i class="bi bi-check-circle-fill"></i>

Role Based Access

</li>

</ul>

<div class="copyright">

© <?php echo date("Y"); ?>

Company Portal

</div>

</div>

</div>

<div class="col-lg-6 d-flex align-items-center justify-content-center">

<div class="login-card">

<div class="text-center mb-4">

<div class="login-icon">

<i class="bi bi-person-circle"></i>

</div>

<h2>

Welcome Back

</h2>

<p>

Please sign in to continue

</p>

</div>

<?= $message ?>

<form method="POST">

<div class="mb-3">

<label>

Username or Email

</label>

<div class="input-group">

<span class="input-group-text">

<i class="bi bi-person-fill"></i>

</span>

<input
type="text"
name="username"
class="form-control"
placeholder="Enter Username or Email"
required>

</div>

</div>

<div class="mb-3">

<label>

Password

</label>

<div class="input-group">

<span class="input-group-text">

<i class="bi bi-lock-fill"></i>

</span>

<input
type="password"
name="password"
id="password"
class="form-control"
placeholder="Enter Password"
required>

<button
class="btn btn-outline-secondary"
type="button"
id="togglePassword">

<i class="bi bi-eye"></i>

</button>

</div>

</div>

<div class="d-flex justify-content-between mb-4">

<div>

<input
type="checkbox"
class="form-check-input"
id="remember">

<label
for="remember"
class="form-check-label">

Remember Me

</label>

</div>

<a href="#">

Forgot Password?

</a>

</div>

<button
type="submit"
name="login"
class="btn btn-primary w-100 login-btn">

<i class="bi bi-box-arrow-in-right"></i>

Login

</button>

</form>

<div class="footer-text">

<div id="clock"></div>

<br>

Company Portal v1.0

</div>

</div>

</div>

</div>

</div>

</div>

<script src="assets/js/login.js"></script>

</body>

</html>
```
