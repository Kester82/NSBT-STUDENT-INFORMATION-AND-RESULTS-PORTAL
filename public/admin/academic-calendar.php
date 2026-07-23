<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/AdminController.php';

AuthController::requireRole(['admin']);

$adminController = new AdminController();

$formData = [
    'reopening_date' => '',
    'vacation_date' => '',
];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!AuthController::csrfTokenIsValid($_POST['csrf_token'] ?? null)) {
        $errors['general'] = 'Your session has expired. Please try again.';
    } elseif (($_POST['action'] ?? '') === 'create') {
        $formData['reopening_date'] = trim(
            (string) ($_POST['reopening_date'] ?? '')
        );

        $formData['vacation_date'] = trim(
            (string) ($_POST['vacation_date'] ?? '')
        );

        $result = $adminController->createAcademicCalendarEntry($formData);

        if ($result['success']) {
            $_SESSION['admin_success'] =
                'Academic calendar entry created successfully.';

            header(
                'Location: '
                . APP_URL
                . '/public/admin/academic-calendar.php'
            );
            exit;
        }

        $errors = $result['errors'];
    } elseif (($_POST['action'] ?? '') === 'delete') {
        $calendarId = filter_var(
            $_POST['calendar_id'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        try {
            if ($calendarId === false) {
                throw new RuntimeException('Invalid calendar entry.');
            }

            $adminController->deleteAcademicCalendarEntry($calendarId);

            $_SESSION['admin_success'] =
                'Academic calendar entry removed successfully.';

            header(
                'Location: '
                . APP_URL
                . '/public/admin/academic-calendar.php'
            );
            exit;
        } catch (RuntimeException $exception) {
            $errors['general'] = $exception->getMessage();
        }
    }
}

$successMessage = $_SESSION['admin_success'] ?? '';
unset($_SESSION['admin_success']);

$calendarEntries = $adminController
    ->getAcademicCalendarData()['calendar_entries'];

$pageTitle = 'Academic Calendar | ' . APP_NAME;

require __DIR__ . '/../../app/views/admin/academic-calendar.php';