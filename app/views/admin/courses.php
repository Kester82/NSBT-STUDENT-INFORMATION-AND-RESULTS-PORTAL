<?php
declare(strict_types=1);

require __DIR__ . '/../../../includes/header.php';

$old = static function (string $field) use ($formData): string {
    return htmlspecialchars((string) ($formData[$field] ?? ''), ENT_QUOTES, 'UTF-8');
};
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
                <div class="mb-4">
                    <p class="text-uppercase small fw-semibold text-primary mb-1">
                        Administration
                    </p>
                    <h1 class="h2 fw-bold mb-1">Manage Courses</h1>
                    <p class="text-muted mb-0">
                        Add courses and review their student and lecturer assignments.
                    </p>
                </div>

                <?php if ($successMessage !== ''): ?>
                    <div class="alert alert-success" role="alert">
                        <?= htmlspecialchars($successMessage) ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($errors['general'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($errors['general']) ?>
                    </div>
                <?php endif; ?>

                <div class="card dashboard-card mb-4">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-3">Add Course</h2>

                        <form method="POST" action="<?= APP_URL ?>/public/admin/courses.php">
                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= htmlspecialchars(AuthController::csrfToken()) ?>"
                            >

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="course_code" class="form-label">Course Code</label>
                                    <input
                                        type="text"
                                        class="form-control <?= isset($errors['course_code']) ? 'is-invalid' : '' ?>"
                                        id="course_code"
                                        name="course_code"
                                        value="<?= $old('course_code') ?>"
                                        placeholder="e.g. IT201"
                                        required
                                    >
                                    <?php if (isset($errors['course_code'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['course_code']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-8">
                                    <label for="course_name" class="form-label">Course Title</label>
                                    <input
                                        type="text"
                                        class="form-control <?= isset($errors['course_name']) ? 'is-invalid' : '' ?>"
                                        id="course_name"
                                        name="course_name"
                                        value="<?= $old('course_name') ?>"
                                        required
                                    >
                                    <?php if (isset($errors['course_name'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['course_name']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-4">
                                    <label for="credit_hours" class="form-label">Credit Hours</label>
                                    <input
                                        type="number"
                                        class="form-control <?= isset($errors['credit_hours']) ? 'is-invalid' : '' ?>"
                                        id="credit_hours"
                                        name="credit_hours"
                                        value="<?= $old('credit_hours') ?>"
                                        min="1"
                                        max="12"
                                        required
                                    >
                                    <?php if (isset($errors['credit_hours'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['credit_hours']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-4">
                                    <label for="semester" class="form-label">Semester</label>
                                    <select
                                        class="form-select <?= isset($errors['semester']) ? 'is-invalid' : '' ?>"
                                        id="semester"
                                        name="semester"
                                        required
                                    >
                                        <option value="">Choose semester</option>
                                        <option value="1" <?= $formData['semester'] === '1' ? 'selected' : '' ?>>
                                            Semester 1
                                        </option>
                                        <option value="2" <?= $formData['semester'] === '2' ? 'selected' : '' ?>>
                                            Semester 2
                                        </option>
                                    </select>
                                    <?php if (isset($errors['semester'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['semester']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-nsbt w-100">
                                        Add Course
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card dashboard-card">
                    <div class="card-body p-0">
                        <div class="p-4 border-bottom">
                            <h2 class="h5 fw-bold mb-0">All Courses</h2>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Code</th>
                                        <th>Course Title</th>
                                        <th>Credits</th>
                                        <th>Semester</th>
                                        <th>Students</th>
                                        <th class="pe-4">Lecturers</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($courses as $course): ?>
                                        <tr>
                                            <td class="ps-4 fw-semibold">
                                                <?= htmlspecialchars($course['course_code']) ?>
                                            </td>
                                            <td><?= htmlspecialchars($course['course_name']) ?></td>
                                            <td><?= htmlspecialchars((string) $course['credit_hours']) ?></td>
                                            <td>Semester <?= htmlspecialchars((string) $course['semester']) ?></td>
                                            <td><?= htmlspecialchars((string) $course['student_count']) ?></td>
                                            <td class="pe-4">
                                                <?= htmlspecialchars((string) $course['lecturer_count']) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>