<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

AuthController::logout();

session_start();
$_SESSION['flash_success'] = 'You have signed out successfully.';

header('Location: ' . APP_URL . '/public/login.php');
exit;