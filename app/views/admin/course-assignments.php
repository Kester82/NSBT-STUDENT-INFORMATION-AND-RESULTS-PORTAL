<?php
declare(strict_types=1);

require __DIR__ . '/../../../includes/header.php';
?>

<section class="dashboard-page py-4">
    <div class="container">
        <div class="row g-4">
            <aside class="col-lg-3">
                <?php
                $activePage = 'assignments';
                require __DIR__ . '/partials/sidebar.php';
                ?>
            </aside>

            <div class="col-lg-9">
                <div class="mb-4">
                    <p class="text-uppercase small fw-semibold text-primary mb-1">
                        Administration
                    </p>
                    <h1 class="h2 fw-bold mb-1">Assign Courses</h1>
                    <p class="text-muted mb-0">
                        Connect lecturers to the courses they are authorised to teach.
                    </p>
                </div>

                <?php if ($successMessage !== ''): ?>
                    <div class="alert alert-success" role="alert">
                        <?= htmlspecialchars($successMessage) ?>
                    </div>
                <?php endif; ?>

                <?php if ($errorMessage !== ''): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($errorMessage) ?>
                    </div>
                <?php endif; ?>

                <div class="card dashboard-card mb-4">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-3">New Course Assignment</h2>

                        <form method="POST" action="<?= APP_URL ?>/public/admin/course-assignments.php">
                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= htmlspecialchars(AuthController::csrfToken()) ?>"
                            >
                            <input type="hidden" name="action" value="assign">

                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label for="lecturer_id" class="form-label">Lecturer</label>
                                    <select class="form-select" id="lecturer_id" name="lecturer_id" required>
                                        <option value="">Choose lecturer</option>

                                        <?php foreach ($lecturers as $lecturer): ?>
                                            <option value="<?= htmlspecialchars((string) $lecturer['lecturer_id']) ?>">
                                                <?= htmlspecialchars($lecturer['full_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-5">
                                    <label for="course_id" class="form-label">Course</label>
                                    <select class="form-select" id="course_id" name="course_id" required>
                                        <option value="">Choose course</option>

                                        <?php foreach ($courses as $course): ?>
                                            <option value="<?= htmlspecialchars((string) $course['course_id']) ?>">
                                                <?= htmlspecialchars($course['course_code']) ?>
                                                —
                                                <?= htmlspecialchars($course['course_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-nsbt w-100">
                                        Assign
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card dashboard-card">
                    <div class="card-body p-0">
                        <div class="p-4 border-bottom">
                            <h2 class="h5 fw-bold mb-0">Current Assignments</h2>
                        </div>

                        <?php if ($assignments === []): ?>
                            <div class="p-4">
                                <p class="text-muted mb-0">
                                    No lecturer course assignments have been created yet.
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Lecturer</th>
                                            <th>Course Code</th>
                                            <th>Course Title</th>
                                            <th class="pe-4 text-end">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($assignments as $assignment): ?>
                                            <tr>
                                                <td class="ps-4 fw-semibold">
                                                    <?= htmlspecialchars($assignment['lecturer_name']) ?>
                                                </td>
                                                <td><?= htmlspecialchars($assignment['course_code']) ?></td>
                                                <td><?= htmlspecialchars($assignment['course_name']) ?></td>
                                                <td class="pe-4 text-end">
                                                    <form method="POST" action="<?= APP_URL ?>/public/admin/course-assignments.php">
                                                        <input
                                                            type="hidden"
                                                            name="csrf_token"
                                                            value="<?= htmlspecialchars(AuthController::csrfToken()) ?>"
                                                        >
                                                        <input type="hidden" name="action" value="remove">
                                                        <input
                                                            type="hidden"
                                                            name="lecturer_id"
                                                            value="<?= htmlspecialchars((string) $assignment['lecturer_id']) ?>"
                                                        >
                                                        <input
                                                            type="hidden"
                                                            name="course_id"
                                                            value="<?= htmlspecialchars((string) $assignment['course_id']) ?>"
                                                        >

                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            Remove
                                                        </button>
                                                    </form>
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