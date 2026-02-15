<?php
$pdo = require "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pdo->exec(
        "INSERT INTO users (name, email) VALUES ('New User', 'test@example.com')",
    );
    echo "User added!";
}
?>

<form method="POST">
    <button type="submit">Add User</button>
</form>
