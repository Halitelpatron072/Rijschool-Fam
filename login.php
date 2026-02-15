<?php

declare(strict_types=1);

require __DIR__ . "/auth.php";

/** @var \PDO $pdo */
$pdo = require __DIR__ . "/config.php";

$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim((string) ($_POST["email"] ?? ""));
    $password = (string) ($_POST["password"] ?? "");

    if ($email === "" || $password === "") {
        $error = "Please enter your email and password.";
    } else {
        $stmt = $pdo->prepare(
            "SELECT id, role, name, email, password_hash FROM users WHERE email = ? LIMIT 1",
        );
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (
            is_array($user) &&
            isset($user["password_hash"]) &&
            is_string($user["password_hash"]) &&
            password_verify($password, $user["password_hash"])
        ) {
            login_user($user);

            header("Location: /dashboard.php");
            exit();
        }

        $error = "Invalid email or password.";
    }
}
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Login</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="form-container">
        <h1>Login</h1>

        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars(
                $error,
                ENT_QUOTES,
                "UTF-8",
            ); ?></p>
        <?php endif; ?>

        <form method="post" action="/login.php" autocomplete="on">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    required
                    autocomplete="email"
                    value="<?php echo htmlspecialchars(
                        (string) ($_POST["email"] ?? ""),
                        ENT_QUOTES,
                        "UTF-8",
                    ); ?>"
                />
            </div>

            <div class="form-group">
                <label for="password">Wachtwoord</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                />
            </div>

            <button type="submit" class="btn">Inloggen</button>
        </form>

        <p class="form-footer">
            <a href="/index.php">Terug naar home</a>
            |
            <a href="/register.php">Registreren</a>
        </p>
    </div>
</body>
</html>
