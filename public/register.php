<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

if (AuthController::isLoggedIn()) {
    header('Location: ' . AuthController::redirectForRole($_SESSION['role']));
    exit;
}

$formData = [
    'full_name' => '',
    'username' => '',
    'index_number' => '',
    'program' => '',
    'year_level' => '',
    'academic_year' => '2025/2026',
    'email' => '',
    'phone' => '',
];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($formData as $field => $value) {
        $formData[$field] = trim((string) ($_POST[$field] ?? ''));
    }

    $csrfToken = $_POST['csrf_token'] ?? null;

    if (!AuthController::csrfTokenIsValid($csrfToken)) {
        $errors['general'] = 'Your session has expired. Please try again.';
    } else {
        $authController = new AuthController();

        $result = $authController->registerStudent([
            ...$formData,
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
        ]);

        if ($result['success']) {
            $_SESSION['flash_success'] = 'Your student account has been created. You can now sign in.';

            header('Location: ' . APP_URL . '/public/login.php');
            exit;
        }

        $errors = $result['errors'];
    }
}

$pageTitle = 'Student Registration | ' . APP_NAME;

require __DIR__ . '/../app/views/auth/register.php';