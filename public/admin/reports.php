<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';
require_once __DIR__ . '/../../app/controllers/AdminController.php';

AuthController::requireRole(['admin']);

$adminController = new AdminController();
$reportsData = $adminController->getReportsData();

$statistics = $reportsData['statistics'];
$programmes = $reportsData['programmes'];
$grades = $reportsData['grades'];

$pageTitle = 'Reports | ' . APP_NAME;

require __DIR__ . '/../../app/views/admin/reports.php';