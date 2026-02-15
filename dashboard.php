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

$scheduleStmt = $pdo->query("
    SELECT id, day_of_week, start_time, end_time, lesson_type, location, notes 
    FROM schedule 
    ORDER BY day_of_week, start_time
");
$schedule = $scheduleStmt->fetchAll();

$dayNames = ["Zondag", "Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag"];
$lessonTypes = array_unique(array_column($schedule, "lesson_type"));
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <div class="dashboard-header">
            <div>
                <h1>Dashboard</h1>
                <div class="dashboard-user">
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

            <nav class="dashboard-nav">
                <a href="/"><i class="fas fa-home"></i> Home</a>
                <a href="/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </div>

        <div class="tabs">
            <button class="tab active" onclick="showTab('users')">Gebruikers</button>
            <button class="tab" onclick="showTab('schedule')">Rooster</button>
        </div>

        <div id="users" class="tab-content active">
            <div class="card">
                <h2><i class="fas fa-users"></i> Gebruikers</h2>

                <?php if (!$users): ?>
                    <p>Geen gebruikers gevonden.</p>
                <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Rol</th>
                                <th>Naam</th>
                                <th>E-mail</th>
                                <th>Aangemaakt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars((string) $row["id"], ENT_QUOTES, "UTF-8"); ?></td>
                                    <td><?php echo htmlspecialchars((string) ($row["role"] ?? ""), ENT_QUOTES, "UTF-8"); ?></td>
                                    <td><?php echo htmlspecialchars((string) ($row["name"] ?? ""), ENT_QUOTES, "UTF-8"); ?></td>
                                    <td><?php echo htmlspecialchars((string) ($row["email"] ?? ""), ENT_QUOTES, "UTF-8"); ?></td>
                                    <td><?php echo htmlspecialchars((string) ($row["created_at"] ?? ""), ENT_QUOTES, "UTF-8"); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <div id="schedule" class="tab-content">
            <div class="card">
                <h2><i class="fas fa-calendar-alt"></i> Weekrooster</h2>

                <div class="schedule-filter">
                    <label>Filter op type:</label>
                    <select id="lessonTypeFilter" onchange="filterSchedule()">
                        <option value="all">Alle types</option>
                        <?php foreach ($lessonTypes as $type): ?>
                            <option value="<?php echo htmlspecialchars($type, ENT_QUOTES, "UTF-8"); ?>">
                                <?php echo htmlspecialchars(ucfirst($type), ENT_QUOTES, "UTF-8"); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="schedule-grid">
                    <?php for ($day = 1; $day <= 6; $day++): ?>
                        <div>
                            <div class="schedule-day-header"><?php echo $dayNames[$day]; ?></div>
                            <div class="schedule-cell" data-day="<?php echo $day; ?>">
                                <?php 
                                $dayLessons = array_filter($schedule, fn($l) => $l["day_of_week"] == $day);
                                foreach ($dayLessons as $lesson): 
                                ?>
                                    <div class="lesson" data-type="<?php echo htmlspecialchars($lesson["lesson_type"], ENT_QUOTES, "UTF-8"); ?>">
                                        <div class="lesson-time">
                                            <?php echo htmlspecialchars(date("H:i", strtotime($lesson["start_time"])), ENT_QUOTES, "UTF-8"); ?> - 
                                            <?php echo htmlspecialchars(date("H:i", strtotime($lesson["end_time"])), ENT_QUOTES, "UTF-8"); ?>
                                        </div>
                                        <div class="lesson-type">
                                            <?php echo htmlspecialchars(ucfirst($lesson["lesson_type"]), ENT_QUOTES, "UTF-8"); ?>
                                            <?php if ($lesson["location"]): ?>
                                                - <?php echo htmlspecialchars($lesson["location"], ENT_QUOTES, "UTF-8"); ?>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($lesson["notes"]): ?>
                                            <div><?php echo htmlspecialchars($lesson["notes"], ENT_QUOTES, "UTF-8"); ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                                <?php if (empty($dayLessons)): ?>
                                    <div class="lesson" style="background: #f0f0f0; color: #999;">Geen lessen</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }

        function filterSchedule() {
            const filterValue = document.getElementById('lessonTypeFilter').value;
            const lessons = document.querySelectorAll('.lesson');
            
            lessons.forEach(lesson => {
                if (filterValue === 'all' || lesson.dataset.type === filterValue) {
                    lesson.style.display = 'block';
                } else {
                    lesson.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
