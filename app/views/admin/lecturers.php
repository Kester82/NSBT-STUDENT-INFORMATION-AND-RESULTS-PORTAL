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
                $activePage = 'lecturers';
                require __DIR__ . '/partials/sidebar.php';
                ?>
            </aside>

            <div class="col-lg-9">
                <div class="mb-4">
                    <p class="text-uppercase small fw-semibold text-primary mb-1">
                        Administration
                    </p>
                    <h1 class="h2 fw-bold mb-1">Manage Lecturers</h1>
                    <p class="text-muted mb-0">
                        Create lecturer accounts and review course assignments.
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
                        <h2 class="h5 fw-bold mb-3">Create Lecturer Account</h2>

                        <form method="POST" action="<?= APP_URL ?>/public/admin/lecturers.php">
                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= htmlspecialchars(AuthController::csrfToken()) ?>"
                            >

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <input
                                        type="text"
                                        class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>"
                                        id="full_name"
                                        name="full_name"
                                        value="<?= $old('full_name') ?>"
                                        required
                                    >
                                    <?php if (isset($errors['full_name'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['full_name']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input
                                        type="email"
                                        class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                        id="email"
                                        name="email"
                                        value="<?= $old('email') ?>"
                                        required
                                    >
                                    <?php if (isset($errors['email'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['email']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <label for="username" class="form-label">Username</label>
                                    <input
                                        type="text"
                                        class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
                                        id="username"
                                        name="username"
                                        value="<?= $old('username') ?>"
                                        required
                                    >
                                    <?php if (isset($errors['username'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['username']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <label for="password" class="form-label">Temporary Password</label>
                                    <input
                                        type="password"
                                        class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                                        id="password"
                                        name="password"
                                        minlength="8"
                                        required
                                    >
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['password']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <input
                                        type="password"
                                        class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>"
                                        id="confirm_password"
                                        name="confirm_password"
                                        minlength="8"
                                        required
                                    >
                                    <?php if (isset($errors['confirm_password'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['confirm_password']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 d-flex align-items-end">
                                    <button type="submit" class="btn btn-nsbt w-100">
                                        Create Lecturer
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card dashboard-card">
                    <div class="card-body p-0">
                        <div class="p-4 border-bottom">
                            <h2 class="h5 fw-bold mb-0">All Lecturers</h2>
                        </div>

                        <?php if ($lecturers === []): ?>
                            <div class="p-4">
                                <p class="text-muted mb-0">No lecturers have been created yet.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Lecturer</th>
                                            <th>Email</th>
                                            <th>Username</th>
                                            <th class="pe-4">Assigned Courses</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($lecturers as $lecturer): ?>
                                            <tr>
                                                <td class="ps-4 fw-semibold">
                                                    <?= htmlspecialchars($lecturer['full_name']) ?>
                                                </td>
                                                <td><?= htmlspecialchars($lecturer['email']) ?></td>
                                                <td><?= htmlspecialchars($lecturer['username']) ?></td>
                                                <td class="pe-4">
                                                    <?= htmlspecialchars((string) $lecturer['assigned_course_count']) ?>
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