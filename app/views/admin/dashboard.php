<?php
declare(strict_types=1);

require __DIR__ . '/../../../includes/header.php';
?>

<section class="dashboard-page py-4">
    <div class="container">
        <div class="row g-4">
            <aside class="col-lg-3">
                <?php
                $activePage = 'dashboard';
                require __DIR__ . '/partials/sidebar.php';
                ?>
            </aside>

            <div class="col-lg-9">
                <div class="mb-4">
                    <p class="text-uppercase small fw-semibold text-primary mb-1">
                        Administration
                    </p>
                    <h1 class="h2 fw-bold mb-1">Admin Dashboard</h1>
                    <p class="text-muted mb-0">
                        Overview of the NSBT Student Portal.
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6 col-xl-3">
                        <div class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <p class="profile-label mb-1">Students</p>
                                <p class="display-6 fw-bold text-primary mb-0">
                                    <?= number_format((int) $statistics['student_count']) ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <p class="profile-label mb-1">Lecturers</p>
                                <p class="display-6 fw-bold text-primary mb-0">
                                    <?= number_format((int) $statistics['lecturer_count']) ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <p class="profile-label mb-1">Courses</p>
                                <p class="display-6 fw-bold text-primary mb-0">
                                    <?= number_format((int) $statistics['course_count']) ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <p class="profile-label mb-1">Results</p>
                                <p class="display-6 fw-bold text-primary mb-0">
                                    <?= number_format((int) $statistics['result_count']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card dashboard-card mt-4">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold">Next actions</h2>
                        <p class="text-muted mb-0">
                            Use the administration tools to manage students,
                            lecturers, courses, results, timetables,
                            announcements, and notifications.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>