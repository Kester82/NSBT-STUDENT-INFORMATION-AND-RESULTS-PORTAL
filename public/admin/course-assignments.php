<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/AdminController.php';

AuthController::requireRole(['admin']);

$adminController = new AdminController();
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!AuthController::csrfTokenIsValid($_POST['csrf_token'] ?? null)) {
        $errorMessage = 'Your session has expired. Please try again.';
    } elseif (($_POST['action'] ?? '') === 'assign') {
        $result = $adminController->assignCourseToLecturer($_POST);

        if ($result['success']) {
            $_SESSION['admin_success'] = 'Course assigned to lecturer successfully.';

            header('Location: ' . APP_URL . '/public/admin/course-assignments.php');
            exit;
        }

        $errorMessage = $result['message'];
    } elseif (($_POST['action'] ?? '') === 'remove') {
        try {
            $adminController->removeCourseAssignment($_POST);

            $_SESSION['admin_success'] = 'Course assignment removed successfully.';

            header('Location: ' . APP_URL . '/public/admin/course-assignments.php');
            exit;
        } catch (RuntimeException $exception) {
            $errorMessage = $exception->getMessage();
        }
    }
}

$successMessage = $_SESSION['admin_success'] ?? '';
unset($_SESSION['admin_success']);

$assignmentData = $adminController->getCourseAssignmentsData();

$lecturers = $assignmentData['lecturers'];
$courses = $assignmentData['courses'];
$assignments = $assignmentData['assignments'];

$pageTitle = 'Assign Courses | ' . APP_NAME;

require __DIR__ . '/../../app/views/admin/course-assignments.php';