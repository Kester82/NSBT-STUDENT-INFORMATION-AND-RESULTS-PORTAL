<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/AdminController.php';

AuthController::requireRole(['admin']);

$adminController = new AdminController();

$formData = [
    'title' => '',
    'message' => '',
];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!AuthController::csrfTokenIsValid($_POST['csrf_token'] ?? null)) {
        $errors['general'] = 'Your session has expired. Please try again.';
    } elseif (($_POST['action'] ?? '') === 'create') {
        $formData['title'] = trim((string) ($_POST['title'] ?? ''));
        $formData['message'] = trim((string) ($_POST['message'] ?? ''));

        $result = $adminController->createAnnouncement($formData);

        if ($result['success']) {
            $_SESSION['admin_success'] = 'Announcement posted successfully.';

            header('Location: ' . APP_URL . '/public/admin/announcements.php');
            exit;
        }

        $errors = $result['errors'];
    } elseif (($_POST['action'] ?? '') === 'delete') {
        $announcementId = filter_var(
            $_POST['announcement_id'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        try {
            if ($announcementId === false) {
                throw new RuntimeException('Invalid announcement.');
            }

            $adminController->deleteAnnouncement($announcementId);

            $_SESSION['admin_success'] = 'Announcement removed successfully.';

            header('Location: ' . APP_URL . '/public/admin/announcements.php');
            exit;
        } catch (RuntimeException $exception) {
            $errors['general'] = $exception->getMessage();
        }
    }
}

$successMessage = $_SESSION['admin_success'] ?? '';
unset($_SESSION['admin_success']);

$announcements = $adminController->getAnnouncementsData()['announcements'];

$pageTitle = 'Manage Announcements | ' . APP_NAME;

require __DIR__ . '/../../app/views/admin/announcements.php';