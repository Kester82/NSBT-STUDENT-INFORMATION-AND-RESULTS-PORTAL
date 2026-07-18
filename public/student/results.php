<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/StudentController.php';

AuthController::requireRole(['student']);

try {
    $studentController = new StudentController();
    $resultsData = $studentController->getResultsForUser((int) $_SESSION['user_id']);
} catch (RuntimeException $exception) {
    error_log('Student results error: ' . $exception->getMessage());

    http_response_code(404);
    exit('Student profile not found.');
}

$student = $resultsData['student'];
$results = $resultsData['results'];
$semesterGpas = $resultsData['semester_gpas'];
$cgpa = $resultsData['cgpa'];
$totalCreditHours = $resultsData['total_credit_hours'];

$pageTitle = 'My Results | ' . APP_NAME;

require __DIR__ . '/../../app/views/student/results.php';