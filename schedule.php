
<?php
/** @var \PDO $pdo */
$pdo = require "config.php";

$res = $pdo->query("SELECT id, name from users")->fetchAll();

foreach ($res as $row) {
    echo $row["name"] . $row["role"];
}
