<?php
declare(strict_types=1);

$activePage = $activePage ?? '';
?>

<div class="dashboard-sidebar">
    <p class="text-uppercase small fw-bold text-white-50 mb-3">
        Lecturer Menu
    </p>

    <a
        class="student-nav-link <?= $activePage === 'dashboard' ? 'active' : '' ?>"
        href="<?= APP_URL ?>/public/lecturer/dashboard.php"
    >
        Dashboard
    </a>

    <a
    class="student-nav-link <?= $activePage === 'results' ? 'active' : '' ?>"
    href="<?= APP_URL ?>/public/lecturer/results.php"
    >
        Upload Results
    </a>

    <hr class="border-light opacity-25">

    <a class="student-nav-link" href="<?= APP_URL ?>/public/logout.php">
        Log out
    </a>
</div>