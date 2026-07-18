<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/StudentController.php';

AuthController::requireRole(['student']);

$feedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? null;

    $notificationId = filter_var(
        $_POST['notification_id'] ?? null,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    if (!AuthController::csrfTokenIsValid($csrfToken)) {
        $feedback = 'Your session has expired. Please try again.';
    } elseif ($notificationId === false) {
        $feedback = 'Invalid notification.';
    } else {
        $studentController = new StudentController();
        $studentController->markNotificationAsRead(
            $notificationId,
            (int) $_SESSION['user_id']
        );

        header('Location: ' . APP_URL . '/public/student/notifications.php?updated=1');
        exit;
    }
}

try {
    $studentController = new StudentController();
    $notificationData = $studentController->getNotificationsForUser(
        (int) $_SESSION['user_id']
    );
} catch (RuntimeException $exception) {
    error_log('Student notifications error: ' . $exception->getMessage());

    http_response_code(404);
    exit('Student profile not found.');
}

$notifications = $notificationData['notifications'];

if (isset($_GET['updated'])) {
    $feedback = 'Notification marked as read.';
}

$pageTitle = 'Notifications | ' . APP_NAME;

require __DIR__ . '/../../app/views/student/notifications.php';