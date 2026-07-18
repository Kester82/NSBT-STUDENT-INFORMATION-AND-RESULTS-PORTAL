<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/LecturerController.php';

AuthController::requireRole(['lecturer']);

try {
    $lecturerController = new LecturerController();
    $dashboardData = $lecturerController->getDashboardData(
        (int) $_SESSION['user_id']
    );
} catch (RuntimeException $exception) {
    error_log('Lecturer dashboard error: ' . $exception->getMessage());

    http_response_code(404);
    exit('Lecturer profile not found.');
}

$lecturer = $dashboardData['lecturer'];
$courses = $dashboardData['courses'];

$courseCount = count($courses);
$totalEnrolments = array_sum(
    array_map(
        static fn (array $course): int => (int) $course['student_count'],
        $courses
    )
);

$pageTitle = 'Lecturer Dashboard | ' . APP_NAME;

require __DIR__ . '/../../app/views/lecturer/dashboard.php';