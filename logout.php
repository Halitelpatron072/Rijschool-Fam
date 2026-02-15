<?php

declare(strict_types=1);

require __DIR__ . '/auth.php';

logout_user();

// Redirect back to login page after logout
header('Location: /login.php');
exit;
