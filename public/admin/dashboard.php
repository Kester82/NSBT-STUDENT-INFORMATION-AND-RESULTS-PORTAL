<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/AdminController.php';

AuthController::requireRole(['admin']);

try {
    $adminController = new AdminController();
    $dashboardData = $adminController->getDashboardData();
} catch (RuntimeException $exception) {
    error_log('Admin dashboard error: ' . $exception->getMessage());

    http_response_code(500);
    exit('Unable to load the admin dashboard.');
}

$statistics = $dashboardData['statistics'];

$pageTitle = 'Admin Dashboard | ' . APP_NAME;

require __DIR__ . '/../../app/views/admin/dashboard.php';