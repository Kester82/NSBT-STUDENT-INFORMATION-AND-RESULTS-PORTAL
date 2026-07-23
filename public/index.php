<?php
require_once __DIR__ . '/../config/app.php';



$pageTitle = 'Welcome';


require_once __DIR__ . '/../includes/header.php';

$role = $_SESSION['role'] ?? null;

$dashboardLinks = [
    'student' => APP_URL . '/public/student/dashboard.php',
    'lecturer' => APP_URL . '/public/lecturer/dashboard.php',
    'admin' => APP_URL . '/public/admin/dashboard.php',
];

$dashboardUrl = $dashboardLinks[$role] ?? null;

?>

<section class="landing-hero">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="badge text-bg-light text-primary mb-3 px-3 py-2">
                    Nduom School of Business and Technology
                </span>

                <h1 class="display-5 fw-bold mb-3">
                    Welcome to the NSBT Student Portal
                </h1>

                <p class="lead mb-4">
                    Access your courses, academic results, timetable, announcements,
                    and important academic information in one secure place.
                </p>

                <?php if ($dashboardUrl): ?>
                    <a href="<?= htmlspecialchars($dashboardUrl) ?>" class="btn btn-light btn-lg me-2">
                        Go to My Dashboard
                    </a>
                    <a href="<?= APP_URL ?>/public/logout.php" class="btn btn-outline-light btn-lg">
                        Log Out
                    </a>
                <?php else: ?>
                    <a href="<?= APP_URL ?>/public/login.php" class="btn btn-light btn-lg me-2">
                        Log In
                    </a>
                    <a href="<?= APP_URL ?>/public/register.php" class="btn btn-outline-light btn-lg">
                        Student Registration
                    </a>
                <?php endif; ?>
            </div>

            <div class="col-lg-5">
                <div class="landing-info-card shadow">
                    <h2 class="h4 text-primary mb-3">Portal services</h2>

                    <div class="mb-3">
                        <h3 class="h6 mb-1">Students</h3>
                        <p class="mb-0 text-muted">
                            View your profile, registered courses, results, timetable, and notices.
                        </p>
                    </div>

                    <div class="mb-3">
                        <h3 class="h6 mb-1">Lecturers</h3>
                        <p class="mb-0 text-muted">
                            View assigned courses and enter or update student results.
                        </p>
                    </div>

                    <div>
                        <h3 class="h6 mb-1">Administrators</h3>
                        <p class="mb-0 text-muted">
                            Manage academic information and keep the portal up to date.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container pb-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold">Everything you need, in one place</h2>
        <p class="text-muted mb-0">
            A simple and secure way to stay connected to your academic journey.
        </p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="h5 text-primary">Academic Records</h3>
                    <p class="text-muted mb-0">
                        Review course results, GPA, CGPA, and enrolled courses.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="h5 text-primary">Stay Informed</h3>
                    <p class="text-muted mb-0">
                        Receive announcements, notifications, timetable details, and calendar dates.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="h5 text-primary">Secure Access</h3>
                    <p class="text-muted mb-0">
                        Role-based dashboards keep student, lecturer, and administrator information separate.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>