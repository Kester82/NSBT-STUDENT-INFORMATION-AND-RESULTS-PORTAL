<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/AdminController.php';

AuthController::requireRole(['admin']);

$adminController = new AdminController();

$formData = [
    'course_code' => '',
    'course_name' => '',
    'credit_hours' => '',
    'semester' => '',
];

$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($formData as $field => $value) {
        $formData[$field] = trim((string) ($_POST[$field] ?? ''));
    }

    if (!AuthController::csrfTokenIsValid($_POST['csrf_token'] ?? null)) {
        $errors['general'] = 'Your session has expired. Please try again.';
    } else {
        $result = $adminController->createCourse($formData);

        if ($result['success']) {
            $_SESSION['admin_success'] = 'Course created successfully.';

            header('Location: ' . APP_URL . '/public/admin/courses.php');
            exit;
        }

        $errors = $result['errors'];
    }
}

if (isset($_SESSION['admin_success'])) {
    $successMessage = $_SESSION['admin_success'];
    unset($_SESSION['admin_success']);
}

$courses = $adminController->getCoursesData()['courses'];

$pageTitle = 'Manage Courses | ' . APP_NAME;

require __DIR__ . '/../../app/views/admin/courses.php';