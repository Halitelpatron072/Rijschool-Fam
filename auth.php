<?php

declare(strict_types=1);

/**
 * Basic session-based auth helpers.
 *
 * Usage:
 * - On protected pages:
 *     require __DIR__ . '/auth.php';
 *     require_login();
 *
 * - After verifying credentials:
 *     login_user($userRowFromDb);
 *
 * - To log out:
 *     logout_user();
 */

if (session_status() !== PHP_SESSION_ACTIVE) {
    // You can customize cookie params if you want stricter defaults.
    session_start();
}

/**
 * @return array{id:int,role:?string,name:?string,email:?string}|null
 */
function current_user(): ?array
{
    $u = $_SESSION['user'] ?? null;
    return is_array($u) ? $u : null;
}

/**
 * @param array{id:mixed,role?:mixed,name?:mixed,email?:mixed} $user
 */
function login_user(array $user): void
{
    // Reduce session fixation risk
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }

    $_SESSION['user'] = [
        'id' => isset($user['id']) ? (int) $user['id'] : 0,
        'role' => array_key_exists('role', $user) && $user['role'] !== null ? (string) $user['role'] : null,
        'name' => array_key_exists('name', $user) && $user['name'] !== null ? (string) $user['name'] : null,
        'email' => array_key_exists('email', $user) && $user['email'] !== null ? (string) $user['email'] : null,
    ];
}

function logout_user(): void
{
    // Clear session array
    $_SESSION = [];

    // Clear cookie (best effort)
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            [
                'expires' => time() - 3600,
                'path' => $params['path'] ?? '/',
                'domain' => $params['domain'] ?? '',
                'secure' => (bool)($params['secure'] ?? false),
                'httponly' => (bool)($params['httponly'] ?? true),
                'samesite' => $params['samesite'] ?? 'Lax',
            ]
        );
    }

    // Destroy session
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
}

function require_login(): void
{
    if (!current_user()) {
        header('Location: /login.php');
        exit;
    }
}

/**
 * Convenience helper if you want role-based checks later.
 */
function require_role(string $role): void
{
    $u = current_user();
    if (!$u || ($u['role'] ?? null) !== $role) {
        header('Location: /login.php');
        exit;
    }
}
