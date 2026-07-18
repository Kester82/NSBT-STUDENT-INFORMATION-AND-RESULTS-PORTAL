<?php
declare(strict_types=1);

require __DIR__ . '/../../../includes/header.php';
?>

<section class="dashboard-page py-4">
    <div class="container">
        <div class="row g-4">
            <aside class="col-lg-3">
                <?php
                $activePage = 'results';
                require __DIR__ . '/partials/sidebar.php';
                ?>
            </aside>

            <div class="col-lg-9">
                <div class="mb-4">
                    <p class="text-uppercase small fw-semibold text-primary mb-1">
                        Academic Performance
                    </p>
                    <h1 class="h2 fw-bold mb-1">My Results</h1>
                    <p class="text-muted mb-0">
                        <?= htmlspecialchars($student['academic_year']) ?> academic year.
                    </p>
                </div>

                <div class="row g-3 mb-4">
                    <?php foreach ($semesterGpas as $semester => $gpa): ?>
                        <div class="col-md-4">
                            <div class="card dashboard-card h-100">
                                <div class="card-body p-4">
                                    <p class="profile-label mb-1">
                                        Semester <?= htmlspecialchars((string) $semester) ?> GPA
                                    </p>
                                    <p class="display-6 fw-bold text-primary mb-0">
                                        <?= number_format((float) $gpa, 2) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="col-md-4">
                        <div class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <p class="profile-label mb-1">CGPA</p>
                                <p class="display-6 fw-bold text-primary mb-0">
                                    <?= number_format((float) $cgpa, 2) ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <p class="profile-label mb-1">Completed Credit Hours</p>
                                <p class="display-6 fw-bold text-primary mb-0">
                                    <?= htmlspecialchars((string) $totalCreditHours) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card dashboard-card">
                    <div class="card-body p-0">
                        <?php if ($results === []): ?>
                            <div class="p-4">
                                <p class="text-muted mb-0">
                                    No results have been published for your account yet.
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Course</th>
                                            <th>Semester</th>
                                            <th>Score</th>
                                            <th>Grade</th>
                                            <th class="pe-4">Grade Point</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($results as $result): ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <span class="fw-semibold">
                                                        <?= htmlspecialchars($result['course_code']) ?>
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?= htmlspecialchars($result['course_name']) ?>
                                                    </small>
                                                </td>

                                                <td>
                                                    Semester <?= htmlspecialchars((string) $result['semester']) ?>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars((string) $result['score']) ?>%
                                                </td>

                                                <td>
                                                    <span class="badge text-bg-primary">
                                                        <?= htmlspecialchars($result['grade']) ?>
                                                    </span>
                                                </td>

                                                <td class="pe-4">
                                                    <?= number_format((float) $result['grade_point'], 1) ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <p class="small text-muted mt-3 mb-0">
                    GPA and CGPA are calculated using the current 4.0 grading scale.
                </p>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>