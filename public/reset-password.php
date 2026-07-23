<?php
declare(strict_types=1);

header('Referrer-Policy: no-referrer');

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

if (AuthController::isLoggedIn()) {
    header('Location: ' . AuthController::redirectForRole($_SESSION['role']));
    exit;
}

$token = trim((string) ($_POST['token'] ?? $_GET['token'] ?? ''));
$error = '';
$message = '';
$resetSuccessful = false;

$authController = new AuthController();
$tokenIsValid = $authController->validatePasswordResetToken($token);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? null;

    if (!AuthController::csrfTokenIsValid($csrfToken)) {
        $error = 'Your session has expired. Please try again.';
    } else {
        $result = $authController->resetPassword(
            $token,
            (string) ($_POST['password'] ?? ''),
            (string) ($_POST['confirm_password'] ?? '')
        );

        if ($result['success']) {
            $message = $result['message'];
            $resetSuccessful = true;
            $tokenIsValid = false;
        } else {
            $error = $result['message'];
        }
    }
}

$pageTitle = 'Reset Password | ' . APP_NAME;

require __DIR__ . '/../app/views/auth/reset-password.php';