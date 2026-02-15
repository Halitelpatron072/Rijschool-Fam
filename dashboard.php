<?php

declare(strict_types=1);

require __DIR__ . "/auth.php";
require_login();

/** @var \PDO $pdo */
$pdo = require __DIR__ . "/config.php";

$user = current_user();

$stmt = $pdo->query(
    "SELECT id, role, name, email, created_at FROM users ORDER BY id DESC",
);
$users = $stmt->fetchAll();
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Dashboard</title>
</head>
<body>
    <header style="display:flex; justify-content:space-between; align-items:center; gap:16px;">
        <div>
            <h1 style="margin: 16px 0 4px;">Dashboard</h1>
            <div style="color:#444;">
                Ingelogd als
                <strong><?php echo htmlspecialchars(
                    (string) ($user["name"] ?? ($user["email"] ?? "user")),
                    ENT_QUOTES,
                    "UTF-8",
                ); ?></strong>
                (<?php echo htmlspecialchars(
                    (string) ($user["email"] ?? ""),
                    ENT_QUOTES,
                    "UTF-8",
                ); ?>)
            </div>
        </div>

        <nav>
            <a href="/">Home</a>
            <a href="/logout.php">Logout</a>
        </nav>
    </header>

    <hr />

    <section>
        <h2>Users</h2>

        <?php if (!$users): ?>
            <p>Geen users gevonden.</p>
        <?php else: ?>
            <table border="1" cellpadding="8" cellspacing="0" style="border-collapse:collapse; width:100%; max-width: 1000px;">
                <thead>
                    <tr>
                        <th align="left">ID</th>
                        <th align="left">Role</th>
                        <th align="left">Name</th>
                        <th align="left">Email</th>
                        <th align="left">Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(
                                (string) $row["id"],
                                ENT_QUOTES,
                                "UTF-8",
                            ); ?></td>
                            <td><?php echo htmlspecialchars(
                                (string) ($row["role"] ?? ""),
                                ENT_QUOTES,
                                "UTF-8",
                            ); ?></td>
                            <td><?php echo htmlspecialchars(
                                (string) ($row["name"] ?? ""),
                                ENT_QUOTES,
                                "UTF-8",
                            ); ?></td>
                            <td><?php echo htmlspecialchars(
                                (string) ($row["email"] ?? ""),
                                ENT_QUOTES,
                                "UTF-8",
                            ); ?></td>
                            <td><?php echo htmlspecialchars(
                                (string) ($row["created_at"] ?? ""),
                                ENT_QUOTES,
                                "UTF-8",
                            ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</body>
</html>
