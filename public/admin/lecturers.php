<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/AdminController.php';

AuthController::requireRole(['admin']);

$adminController = new AdminController();

$formData = [
    'full_name' => '',
    'email' => '',
    'username' => '',
];

$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['full_name'] = trim((string) ($_POST['full_name'] ?? ''));
    $formData['email'] = trim((string) ($_POST['email'] ?? ''));
    $formData['username'] = trim((string) ($_POST['username'] ?? ''));

    if (!AuthController::csrfTokenIsValid($_POST['csrf_token'] ?? null)) {
        $errors['general'] = 'Your session has expired. Please try again.';
    } else {
        $result = $adminController->createLecturer([
            ...$formData,
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
        ]);

        if ($result['success']) {
            $_SESSION['admin_success'] = 'Lecturer account created successfully.';

            header('Location: ' . APP_URL . '/public/admin/lecturers.php');
            exit;
        }

        $errors = $result['errors'];
    }
}

if (isset($_SESSION['admin_success'])) {
    $successMessage = $_SESSION['admin_success'];
    unset($_SESSION['admin_success']);
}

$lecturers = $adminController->getLecturersData()['lecturers'];

$pageTitle = 'Manage Lecturers | ' . APP_NAME;

require __DIR__ . '/../../app/views/admin/lecturers.php';