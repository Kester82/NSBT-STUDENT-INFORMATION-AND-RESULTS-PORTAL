<?php
declare(strict_types=1);

$activePage = $activePage ?? '';
?>

<div class="dashboard-sidebar">
    <p class="text-uppercase small fw-bold text-white-50 mb-3">
        Admin Menu
    </p>

    <a
        class="student-nav-link <?= $activePage === 'dashboard' ? 'active' : '' ?>"
        href="<?= APP_URL ?>/public/admin/dashboard.php"
    >
        Dashboard
    </a>

    <a
        class="student-nav-link <?= $activePage === 'students' ? 'active' : '' ?>"
        href="<?= APP_URL ?>/public/admin/students.php"
    >
        Manage Students
    </a>

    <a
    class="student-nav-link <?= $activePage === 'lecturers' ? 'active' : '' ?>"
    href="<?= APP_URL ?>/public/admin/lecturers.php"
    >
        Manage Lecturers
    </a>

    <a
    class="student-nav-link <?= $activePage === 'courses' ? 'active' : '' ?>"
    href="<?= APP_URL ?>/public/admin/courses.php"
    >
        Manage Courses
    </a>

    <a
    class="student-nav-link <?= $activePage === 'assignments' ? 'active' : '' ?>"
    href="<?= APP_URL ?>/public/admin/course-assignments.php"
    >
        Assign Courses
    </a>

    <a
    class="student-nav-link <?= $activePage === 'announcements' ? 'active' : '' ?>"
    href="<?= APP_URL ?>/public/admin/announcements.php"
    >
        Announcements
    </a>

    <a
    class="student-nav-link <?= $activePage === 'notifications' ? 'active' : '' ?>"
    href="<?= APP_URL ?>/public/admin/notifications.php"
    >
        Notifications
    </a>

    <a
    class="student-nav-link <?= $activePage === 'timetable' ? 'active' : '' ?>"
    href="<?= APP_URL ?>/public/admin/timetable.php"
    >
        Timetable
    </a>

    <a
    class="student-nav-link <?= $activePage === 'results' ? 'active' : '' ?>"
    href="<?= APP_URL ?>/public/admin/results.php"
>
    Manage Results
</a>

<a
    class="student-nav-link <?= $activePage === 'calendar' ? 'active' : '' ?>"
    href="<?= APP_URL ?>/public/admin/academic-calendar.php"
>
    Academic Calendar
</a>

<a
    class="student-nav-link <?= $activePage === 'reports' ? 'active' : '' ?>"
    href="<?= APP_URL ?>/public/admin/reports.php"
>
    Reports
</a>

    <hr class="border-light opacity-25">

    <a class="student-nav-link" href="<?= APP_URL ?>/public/logout.php">
        Log out
    </a>
</div>