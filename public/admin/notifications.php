<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/AdminController.php';

AuthController::requireRole(['admin']);

$adminController = new AdminController();

$formData = [
    'user_id' => '',
    'message' => '',
];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!AuthController::csrfTokenIsValid($_POST['csrf_token'] ?? null)) {
        $errors['general'] = 'Your session has expired. Please try again.';
    } elseif (($_POST['action'] ?? '') === 'create') {
        $formData['user_id'] = trim((string) ($_POST['user_id'] ?? ''));
        $formData['message'] = trim((string) ($_POST['message'] ?? ''));

        $result = $adminController->createNotification($formData);

        if ($result['success']) {
            $_SESSION['admin_success'] = 'Notification sent successfully.';

            header('Location: ' . APP_URL . '/public/admin/notifications.php');
            exit;
        }

        $errors = $result['errors'];
    } elseif (($_POST['action'] ?? '') === 'delete') {
        $notificationId = filter_var(
            $_POST['notification_id'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        try {
            if ($notificationId === false) {
                throw new RuntimeException('Invalid notification.');
            }

            $adminController->deleteNotification($notificationId);

            $_SESSION['admin_success'] = 'Notification removed successfully.';

            header('Location: ' . APP_URL . '/public/admin/notifications.php');
            exit;
        } catch (RuntimeException $exception) {
            $errors['general'] = $exception->getMessage();
        }
    }
}

$successMessage = $_SESSION['admin_success'] ?? '';
unset($_SESSION['admin_success']);

$notificationData = $adminController->getNotificationsData();

$students = $notificationData['students'];
$notifications = $notificationData['notifications'];

$pageTitle = 'Manage Notifications | ' . APP_NAME;

require __DIR__ . '/../../app/views/admin/notifications.php';