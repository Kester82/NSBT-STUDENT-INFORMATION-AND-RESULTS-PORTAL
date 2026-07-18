<?php
declare(strict_types=1);

$activePage = $activePage ?? '';
?>

<div class="dashboard-sidebar">
    <p class="text-uppercase small fw-bold text-white-50 mb-3">
        Student Menu
    </p>

    <a
        class="student-nav-link <?= $activePage === 'dashboard' ? 'active' : '' ?>"
        href="<?= APP_URL ?>/public/student/dashboard.php"
    >
        Dashboard
    </a>

    <a
        class="student-nav-link <?= $activePage === 'profile' ? 'active' : '' ?>"
        href="<?= APP_URL ?>/public/student/profile.php"
    >
        Profile
    </a>

    <a
    class="student-nav-link <?= $activePage === 'notifications' ? 'active' : '' ?>"
    href="<?= APP_URL ?>/public/student/notifications.php"
>
    Notifications
</a>

    <a
    class="student-nav-link <?= $activePage === 'courses' ? 'active' : '' ?>"
    href="<?= APP_URL ?>/public/student/courses.php"
    >
        Courses
    </a>

    <a
    class="student-nav-link <?= $activePage === 'results' ? 'active' : '' ?>"
    href="<?= APP_URL ?>/public/student/results.php"
    >
        Results
    </a>
    <a
    class="student-nav-link <?= $activePage === 'timetable' ? 'active' : '' ?>"
    href="<?= APP_URL ?>/public/student/timetable.php"
    >
        Timetable
    </a>
    <a
    class="student-nav-link <?= $activePage === 'announcements' ? 'active' : '' ?>"
    href="<?= APP_URL ?>/public/student/announcements.php"
    >
        Announcements
    </a>


    <a
    class="student-nav-link <?= $activePage === 'downloads' ? 'active' : '' ?>"
    href="<?= APP_URL ?>/public/student/downloads.php"
    >
        Downloads
    </a>

    <hr class="border-light opacity-25">

    <a class="student-nav-link" href="<?= APP_URL ?>/public/logout.php">
        Log out
    </a>
</div>