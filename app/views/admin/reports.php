<?php
declare(strict_types=1);

require __DIR__ . '/../../../includes/header.php';
?>

<section class="dashboard-page py-4">
    <div class="container">
        <div class="row g-4">
            <aside class="col-lg-3">
                <?php
                $activePage = 'reports';
                require __DIR__ . '/partials/sidebar.php';
                ?>
            </aside>

            <div class="col-lg-9">
                <div class="mb-4">
                    <p class="text-uppercase small fw-semibold text-primary mb-1">
                        Administration
                    </p>
                    <h1 class="h2 fw-bold mb-1">Reports</h1>
                    <p class="text-muted mb-0">
                        Summary of the current portal data.
                    </p>
                </div>

                <div class="row g-3 mb-4">
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
                                <p class="profile-label mb-1">Published Results</p>
                                <p class="display-6 fw-bold text-primary mb-0">
                                    <?= number_format((int) $statistics['result_count']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card dashboard-card h-100">
                            <div class="card-body p-0">
                                <div class="p-4 border-bottom">
                                    <h2 class="h5 fw-bold mb-0">Students by Programme</h2>
                                </div>

                                <?php if ($programmes === []): ?>
                                    <div class="p-4">
                                        <p class="text-muted mb-0">
                                            No student data is available yet.
                                        </p>
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-4">Programme</th>
                                                    <th class="pe-4 text-end">Students</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($programmes as $programme): ?>
                                                    <tr>
                                                        <td class="ps-4">
                                                            <?= htmlspecialchars($programme['program']) ?>
                                                        </td>
                                                        <td class="pe-4 text-end fw-semibold">
                                                            <?= htmlspecialchars((string) $programme['student_count']) ?>
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

                    <div class="col-md-6">
                        <div class="card dashboard-card h-100">
                            <div class="card-body p-0">
                                <div class="p-4 border-bottom">
                                    <h2 class="h5 fw-bold mb-0">Grade Distribution</h2>
                                </div>

                                <?php if ($grades === []): ?>
                                    <div class="p-4">
                                        <p class="text-muted mb-0">
                                            No results have been published yet.
                                        </p>
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-4">Grade</th>
                                                    <th class="pe-4 text-end">Results</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($grades as $grade): ?>
                                                    <tr>
                                                        <td class="ps-4">
                                                            <span class="badge text-bg-primary">
                                                                <?= htmlspecialchars($grade['grade']) ?>
                                                            </span>
                                                        </td>
                                                        <td class="pe-4 text-end fw-semibold">
                                                            <?= htmlspecialchars((string) $grade['result_count']) ?>
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
        </div>
    </div>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>