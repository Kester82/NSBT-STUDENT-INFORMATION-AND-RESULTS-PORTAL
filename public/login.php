<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

if (AuthController::isLoggedIn()) {
    header('Location: ' . AuthController::redirectForRole($_SESSION['role']));
    exit;
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? null;

    if (!AuthController::csrfTokenIsValid($csrfToken)) {
        $error = 'Your session has expired. Please try again.';
    } else {
        $authController = new AuthController();
        $result = $authController->login($username, $password);

        if ($result['success']) {
            header('Location: ' . AuthController::redirectForRole($result['role']));
            exit;
        }

        $error = $result['message'];
    }
}

$success = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_success']);

$pageTitle = 'Login | ' . APP_NAME;

require __DIR__ . '/../app/views/auth/login.php';