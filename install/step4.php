<?php

session_start();

if (!isset($_SESSION['db_host'])) {
    header("Location: index.php");
    exit;
}

/*
----------------------------------------------------
Generate database.php
----------------------------------------------------
*/

$config = <<<PHP
<?php

declare(strict_types=1);

class Database
{
    private string \$host = "{$_SESSION['db_host']}";
    private string \$database = "{$_SESSION['db_name']}";
    private string \$username = "{$_SESSION['db_user']}";
    private string \$password = "{$_SESSION['db_pass']}";
    private string \$charset = "utf8mb4";

    private ?PDO \$connection = null;

    public function connect(): PDO
    {
        if (\$this->connection instanceof PDO) {
            return \$this->connection;
        }

        \$dsn = "mysql:host={\$this->host};dbname={\$this->database};charset={\$this->charset}";

        \$this->connection = new PDO(
            \$dsn,
            \$this->username,
            \$this->password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );

        return \$this->connection;
    }
}

\$db = new Database();
\$pdo = \$db->connect();

PHP;

/*
----------------------------------------------------
Write database.php
----------------------------------------------------
*/

$configFile = "../config/database.php";

/*
Do NOT delete the file.
Simply overwrite it.
*/

if (file_put_contents($configFile, $config, LOCK_EX) === false) {
    die("
    <h2>Installation Failed</h2>
    <p>Unable to write <strong>config/database.php</strong>.</p>
    <p>Please make sure the <strong>config</strong> folder has write permission.</p>
    ");
}

/*
----------------------------------------------------
Create installed.lock
----------------------------------------------------
*/

if (file_put_contents("../config/installed.lock", date("Y-m-d H:i:s"), LOCK_EX) === false) {
    die("
    <h2>Installation Failed</h2>
    <p>Unable to create <strong>installed.lock</strong>.</p>
    ");
}

/*
----------------------------------------------------
Destroy Installer Session
----------------------------------------------------
*/

session_unset();
session_destroy();

/*
----------------------------------------------------
Redirect
----------------------------------------------------
*/

header("Location: finish.php");
exit;