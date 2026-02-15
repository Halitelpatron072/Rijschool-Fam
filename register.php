<?php

declare(strict_types=1);

require __DIR__ . "/auth.php";

/** @var \PDO $pdo */
$pdo = require __DIR__ . "/config.php";

$error = null;
$success = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim((string) ($_POST["name"] ?? ""));
    $email = trim((string) ($_POST["email"] ?? ""));
    $password = (string) ($_POST["password"] ?? "");
    $passwordConfirm = (string) ($_POST["password_confirm"] ?? "");

    if (
        $name === "" ||
        $email === "" ||
        $password === "" ||
        $passwordConfirm === ""
    ) {
        $error = "Vul alle velden in.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Vul een geldig e-mailadres in.";
    } elseif (strlen($password) < 8) {
        $error = "Wachtwoord moet minimaal 8 tekens zijn.";
    } elseif ($password !== $passwordConfirm) {
        $error = "Wachtwoorden komen niet overeen.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $existing = $stmt->fetch();

        if ($existing) {
            $error = "Dit e-mailadres is al geregistreerd.";
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $insert = $pdo->prepare('
                INSERT INTO users (role, name, email, password_hash)
                VALUES (?, ?, ?, ?)
            ');
            $insert->execute(["student", $name, $email, $passwordHash]);

            $userId = (int) $pdo->lastInsertId();

            login_user([
                "id" => $userId,
                "role" => $role,
                "name" => $name,
                "email" => $email,
            ]);

            header("Location: /dashboard.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registreren</title>
</head>
<body>
    <h1>Registreren</h1>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars(
            $error,
            ENT_QUOTES,
            "UTF-8",
        ); ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color: green;"><?php echo htmlspecialchars(
            $success,
            ENT_QUOTES,
            "UTF-8",
        ); ?></p>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <label for="name">Naam:</label><br />
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars(
            (string) ($_POST["name"] ?? ""),
            ENT_QUOTES,
            "UTF-8",
        ); ?>" required /><br /><br />

        <label for="email">E-mail:</label><br />
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars(
            (string) ($_POST["email"] ?? ""),
            ENT_QUOTES,
            "UTF-8",
        ); ?>" required /><br /><br />

        <label for="password">Wachtwoord:</label><br />
        <input type="password" id="password" name="password" required /><br /><br />

        <label for="password_confirm">Herhaal wachtwoord:</label><br />
        <input type="password" id="password_confirm" name="password_confirm" required /><br /><br />

        <button type="submit">Account aanmaken</button>
    </form>

    <p>
        Heb je al een account?
        <a href="/login.php">Log in</a>
    </p>
</body>
</html>
