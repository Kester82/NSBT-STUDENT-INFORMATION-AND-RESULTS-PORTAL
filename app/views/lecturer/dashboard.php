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
                <div class="dashboard-welcome mb-4">
                    <p class="text-uppercase small fw-semibold mb-1">Lecturer Portal</p>
                    <h1 class="h2 fw-bold mb-2">
                        Welcome, <?= htmlspecialchars($lecturer['full_name']) ?>
                    </h1>
                    <p class="mb-0">
                        Manage results for your assigned courses.
                    </p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <p class="profile-label mb-1">Assigned Courses</p>
                                <p class="display-6 fw-bold text-primary mb-0">
                                    <?= $courseCount ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <p class="profile-label mb-1">Course Enrolments</p>
                                <p class="display-6 fw-bold text-primary mb-0">
                                    <?= $totalEnrolments ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card dashboard-card">
                    <div class="card-body p-0">
                        <div class="p-4 border-bottom">
                            <h2 class="h5 fw-bold mb-0">My Assigned Courses</h2>
                        </div>

                        <?php if ($courses === []): ?>
                            <div class="p-4">
                                <p class="text-muted mb-0">
                                    You have not been assigned to any courses yet.
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Course Code</th>
                                            <th>Course Title</th>
                                            <th>Credit Hours</th>
                                            <th>Semester</th>
                                            <th class="pe-4">Enrolments</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($courses as $course): ?>
                                            <tr>
                                                <td class="ps-4 fw-semibold">
                                                    <?= htmlspecialchars($course['course_code']) ?>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars($course['course_name']) ?>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars((string) $course['credit_hours']) ?>
                                                </td>

                                                <td>
                                                    Semester <?= htmlspecialchars((string) $course['semester']) ?>
                                                </td>

                                                <td class="pe-4">
                                                    <?= htmlspecialchars((string) $course['student_count']) ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>