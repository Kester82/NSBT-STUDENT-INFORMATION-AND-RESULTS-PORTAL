<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';

if (session_status() === PHP_SESSION_NONE) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (int) ($_SERVER['SERVER_PORT'] ?? 80) === 443;

    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');

    session_name('nsbt_portal_session');

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => APP_URL . '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}


$pageTitle = $pageTitle ?? APP_NAME;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark portal-navbar">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= APP_URL ?>/public/index.php">
                NSBT Student Portal
            </a>
        </div>
    </nav>

    <main>