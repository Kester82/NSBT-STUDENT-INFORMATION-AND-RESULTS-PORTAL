<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/AdminController.php';

AuthController::requireRole(['admin']);

$adminController = new AdminController();
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
            $savedCount = $adminController->saveResultScores(
                $courseId,
                $enteredScores
            );

            $_SESSION['admin_success'] =
                "{$savedCount} result(s) saved successfully.";

            header(
                'Location: ' . APP_URL
                . '/public/admin/results.php?course_id=' . $courseId
            );
            exit;
        } catch (RuntimeException $exception) {
            $feedback = $exception->getMessage();
        }
    }
}

if (isset($_SESSION['admin_success'])) {
    $feedback = $_SESSION['admin_success'];
    $feedbackType = 'success';

    unset($_SESSION['admin_success']);
}

try {
    $resultData = $adminController->getResultsData($courseId);
} catch (RuntimeException $exception) {
    $feedback = 'The selected course is unavailable.';
    $courseId = null;
    $resultData = $adminController->getResultsData(null);
}

$courses = $resultData['courses'];
$selectedCourse = $resultData['selected_course'];
$students = $resultData['students'];

$pageTitle = 'Manage Results | ' . APP_NAME;

require __DIR__ . '/../../app/views/admin/results.php';