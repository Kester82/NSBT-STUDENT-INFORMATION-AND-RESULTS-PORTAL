<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/AdminController.php';

AuthController::requireRole(['admin']);

try {
    $adminController = new AdminController();
    $studentsData = $adminController->getStudentsData();
} catch (RuntimeException $exception) {
    error_log('Admin student list error: ' . $exception->getMessage());

    http_response_code(500);
    exit('Unable to load students.');
}

$students = $studentsData['students'];

$pageTitle = 'Manage Students | ' . APP_NAME;

require __DIR__ . '/../../app/views/admin/students.php';