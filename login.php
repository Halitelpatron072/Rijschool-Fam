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
</head>
<body>
    <main style="max-width: 420px; margin: 40px auto; font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;">
        <h1>Login</h1>

        <?php if ($error): ?>
            <p style="color:#b00020;"><?php echo htmlspecialchars(
                $error,
                ENT_QUOTES,
                "UTF-8",
            ); ?></p>
        <?php endif; ?>

        <form method="post" action="/login.php" autocomplete="on">
            <div style="margin-bottom: 12px;">
                <label for="email">E-mail</label><br />
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
                    style="width: 100%; padding: 8px;"
                />
            </div>

            <div style="margin-bottom: 12px;">
                <label for="password">Wachtwoord</label><br />
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    style="width: 100%; padding: 8px;"
                />
            </div>

            <button type="submit" style="padding: 10px 14px;">Inloggen</button>
        </form>

        <p style="margin-top: 16px;">
            <a href="/index.php">Terug naar home</a>
            |
            <a href="/register.php">Registreren</a>
        </p>
    </main>
</body>
</html>
