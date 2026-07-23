<?php
declare(strict_types=1);

require __DIR__ . '/../../../includes/header.php';
?>

<section class="dashboard-page py-4">
    <div class="container">
        <div class="row g-4">
            <aside class="col-lg-3">
                <?php
                $activePage = 'students';
                require __DIR__ . '/partials/sidebar.php';
                ?>
            </aside>

            <div class="col-lg-9">
                <div class="mb-4">
                    <p class="text-uppercase small fw-semibold text-primary mb-1">
                        Administration
                    </p>
                    <h1 class="h2 fw-bold mb-1">Manage Students</h1>
                    <p class="text-muted mb-0">
                        View all registered NSBT students.
                    </p>
                </div>

                <div class="card dashboard-card">
                    <div class="card-body p-0">
                        <?php if ($students === []): ?>
                            <div class="p-4">
                                <p class="text-muted mb-0">
                                    No students have been registered yet.
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Student</th>
                                            <th>Index Number</th>
                                            <th>Programme</th>
                                            <th>Level</th>
                                            <th>Email</th>
                                            <th class="pe-4">Username</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($students as $student): ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <span class="fw-semibold">
                                                        <?= htmlspecialchars($student['full_name']) ?>
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?= htmlspecialchars($student['academic_year']) ?>
                                                    </small>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars($student['index_number']) ?>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars($student['program']) ?>
                                                </td>

                                                <td>
                                                    Year <?= htmlspecialchars((string) $student['year_level']) ?>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars($student['email']) ?>
                                                </td>

                                                <td class="pe-4">
                                                    <?= htmlspecialchars($student['username']) ?>
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