<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/LecturerController.php';

AuthController::requireRole(['lecturer']);

$lecturerController = new LecturerController();
$feedback = '';
$feedbackType = 'danger';
$enteredScores = [];

$courseIdInput = $_GET['course_id'] ?? $_POST['course_id'] ?? null;

$courseId = filter_var(
    $courseIdInput,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

$courseId = $courseId === false ? null : $courseId;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredScores = is_array($_POST['scores'] ?? null)
        ? $_POST['scores']
        : [];

    if (!AuthController::csrfTokenIsValid($_POST['csrf_token'] ?? null)) {
        $feedback = 'Your session has expired. Please try again.';
    } elseif ($courseId === null) {
        $feedback = 'Choose a valid course.';
    } else {
        try {
            $savedCount = $lecturerController->saveResultScores(
                (int) $_SESSION['user_id'],
                $courseId,
                $enteredScores
            );

            $_SESSION['lecturer_success'] =
                "{$savedCount} result(s) saved successfully.";

            header(
                'Location: ' . APP_URL
                . '/public/lecturer/results.php?course_id=' . $courseId
            );
            exit;
        } catch (RuntimeException $exception) {
            $feedback = $exception->getMessage();
        }
    }
}

if (isset($_SESSION['lecturer_success'])) {
    $feedback = $_SESSION['lecturer_success'];
    $feedbackType = 'success';

    unset($_SESSION['lecturer_success']);
}

try {
    $resultEntryData = $lecturerController->getResultEntryData(
        (int) $_SESSION['user_id'],
        $courseId
    );
} catch (RuntimeException $exception) {
    error_log('Lecturer result-entry error: ' . $exception->getMessage());

    $feedback = 'The selected course is unavailable.';
    $feedbackType = 'danger';
    $courseId = null;

    $resultEntryData = $lecturerController->getResultEntryData(
        (int) $_SESSION['user_id'],
        null
    );
}

$courses = $resultEntryData['courses'];
$selectedCourse = $resultEntryData['selected_course'];
$students = $resultEntryData['students'];

$pageTitle = 'Upload Results | ' . APP_NAME;

require __DIR__ . '/../../app/views/lecturer/results.php';