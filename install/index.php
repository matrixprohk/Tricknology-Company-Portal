<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Company Portal Installer</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="installer-box">

    <h1>Company Portal</h1>

    <h2>Installation Wizard</h2>

    <p>
        Welcome to the Company Portal Installer.
    </p>

    <p>
        This wizard will guide you through the installation.
    </p>

    <hr>

    <ul class="check-list">

        <li>✔ PHP Installed</li>
        <li>✔ MySQL Installed</li>
        <li>✔ PDO Extension</li>
        <li>✔ Upload Folder</li>

    </ul>

    <a href="step1.php" class="btn">
        Start Installation →
    </a>

</div>

</body>

</html>