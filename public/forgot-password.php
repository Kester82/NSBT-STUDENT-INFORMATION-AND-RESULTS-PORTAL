<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

if (AuthController::isLoggedIn()) {
    header('Location: ' . AuthController::redirectForRole($_SESSION['role']));
    exit;
}

$email = '';
$error = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $csrfToken = $_POST['csrf_token'] ?? null;

    if (!AuthController::csrfTokenIsValid($csrfToken)) {
        $error = 'Your session has expired. Please try again.';
    } else {
        $authController = new AuthController();
        $result = $authController->requestPasswordReset($email);

        if ($result['success']) {
            $message = $result['message'];
        } else {
            $error = $result['message'];
        }
    }
}

$pageTitle = 'Forgot Password | ' . APP_NAME;

require __DIR__ . '/../app/views/auth/forgot-password.php';