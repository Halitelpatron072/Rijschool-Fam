
<?php
$pdo = require "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pdo->exec(
        "INSERT INTO users (name, email)
        VALUES ('New User', 'test@example.com')",
    );
    echo "User added!";
}
?>

<form method="POST">
    <label for="name">Naam:</label>
    <input type="text" id="name" name="name"><br><
    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email"><br><br>
    <input type="submit" value="Submit">
</form>
