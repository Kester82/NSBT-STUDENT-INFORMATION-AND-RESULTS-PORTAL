<?php
declare(strict_types=1);

require __DIR__ . '/../../../includes/header.php';

$totalCredits = 0;

foreach ($courses as $course) {
    $totalCredits += (int) $course['credit_hours'];
}
?>

<section class="dashboard-page py-4">
    <div class="container">
        <div class="row g-4">
            <aside class="col-lg-3">
                <?php
                $activePage = 'courses';
                require __DIR__ . '/partials/sidebar.php';
                ?>
            </aside>

            <div class="col-lg-9">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                    <div>
                        <p class="text-uppercase small fw-semibold text-primary mb-1">
                            Academic Information
                        </p>
                        <h1 class="h2 fw-bold mb-1">My Courses</h1>
                        <p class="text-muted mb-0">
                            Courses registered for <?= htmlspecialchars($student['academic_year']) ?>.
                        </p>
                    </div>

                    <div class="mt-3 mt-md-0">
                        <span class="badge text-bg-primary fs-6">
                            <?= $totalCredits ?> Credit Hours
                        </span>
                    </div>
                </div>

                <div class="card dashboard-card">
                    <div class="card-body p-0">
                        <?php if ($courses === []): ?>
                            <div class="p-4">
                                <p class="text-muted mb-0">
                                    No courses have been registered for your account yet.
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
                                            <th class="pe-4">Semester</th>
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

                                                <td class="pe-4">
                                                    Semester <?= htmlspecialchars((string) $course['semester']) ?>
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