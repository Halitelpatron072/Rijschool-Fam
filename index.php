<?php
$pdo = require "config.php";

$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();

foreach ($users as $user) {
    echo $user["name"] . " - " . $user["email"] . "<br>";
}
