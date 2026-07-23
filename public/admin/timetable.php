<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/AdminController.php';

AuthController::requireRole(['admin']);

$adminController = new AdminController();

$formData = [
    'course_id' => '',
    'day' => '',
    'time' => '',
    'room' => '',
];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!AuthController::csrfTokenIsValid($_POST['csrf_token'] ?? null)) {
        $errors['general'] = 'Your session has expired. Please try again.';
    } elseif (($_POST['action'] ?? '') === 'create') {
        foreach ($formData as $field => $value) {
            $formData[$field] = trim((string) ($_POST[$field] ?? ''));
        }

        $result = $adminController->createTimetableEntry($formData);

        if ($result['success']) {
            $_SESSION['admin_success'] = 'Timetable entry created successfully.';

            header('Location: ' . APP_URL . '/public/admin/timetable.php');
            exit;
        }

        $errors = $result['errors'];
    } elseif (($_POST['action'] ?? '') === 'delete') {
        $timetableId = filter_var(
            $_POST['timetable_id'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        try {
            if ($timetableId === false) {
                throw new RuntimeException('Invalid timetable entry.');
            }

            $adminController->deleteTimetableEntry($timetableId);

            $_SESSION['admin_success'] = 'Timetable entry removed successfully.';

            header('Location: ' . APP_URL . '/public/admin/timetable.php');
            exit;
        } catch (RuntimeException $exception) {
            $errors['general'] = $exception->getMessage();
        }
    }
}

$successMessage = $_SESSION['admin_success'] ?? '';
unset($_SESSION['admin_success']);

$timetableData = $adminController->getTimetableData();

$courses = $timetableData['courses'];
$entries = $timetableData['entries'];

$pageTitle = 'Manage Timetable | ' . APP_NAME;

require __DIR__ . '/../../app/views/admin/timetable.php';