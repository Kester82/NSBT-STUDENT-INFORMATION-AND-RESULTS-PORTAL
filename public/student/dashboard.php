<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/StudentController.php';

AuthController::requireRole(['student']);

try {
    $studentController = new StudentController();
    $dashboardData = $studentController->getDashboardData((int) $_SESSION['user_id']);
} catch (RuntimeException $exception) {
    error_log('Student dashboard error: ' . $exception->getMessage());

    http_response_code(404);
    exit('Student profile not found.');
}

$student = $dashboardData['student'];
$notifications = $dashboardData['notifications'];
$announcements = $dashboardData['announcements'];

$pageTitle = 'Student Dashboard | ' . APP_NAME;

require __DIR__ . '/../../app/views/student/dashboard.php';