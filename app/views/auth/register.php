<?php
declare(strict_types=1);

require __DIR__ . '/../../../includes/header.php';

$old = static function (string $field) use ($formData): string {
    return htmlspecialchars((string) ($formData[$field] ?? ''), ENT_QUOTES, 'UTF-8');
};
?>

<section class="auth-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card auth-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h1 class="h3 fw-bold">Student Registration</h1>
                            <p class="text-muted mb-0">
                                Create your NSBT Student Portal account.
                            </p>
                        </div>

                        <?php if (isset($errors['general'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($errors['general']) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?= APP_URL ?>/public/register.php">
                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= htmlspecialchars(AuthController::csrfToken()) ?>"
                            >

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <input
                                        type="text"
                                        class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>"
                                        id="full_name"
                                        name="full_name"
                                        value="<?= $old('full_name') ?>"
                                        autocomplete="name"
                                        required
                                    >
                                    <?php if (isset($errors['full_name'])): ?>
                                        <div class="invalid-feedback"><?= htmlspecialchars($errors['full_name']) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input
                                        type="text"
                                        class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
                                        id="username"
                                        name="username"
                                        value="<?= $old('username') ?>"
                                        autocomplete="username"
                                        required
                                    >
                                    <?php if (isset($errors['username'])): ?>
                                        <div class="invalid-feedback"><?= htmlspecialchars($errors['username']) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="index_number" class="form-label">Index Number</label>
                                    <input
                                        type="text"
                                        class="form-control <?= isset($errors['index_number']) ? 'is-invalid' : '' ?>"
                                        id="index_number"
                                        name="index_number"
                                        value="<?= $old('index_number') ?>"
                                        required
                                    >
                                    <?php if (isset($errors['index_number'])): ?>
                                        <div class="invalid-feedback"><?= htmlspecialchars($errors['index_number']) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="program" class="form-label">Programme</label>
                                    <input
                                        type="text"
                                        class="form-control <?= isset($errors['program']) ? 'is-invalid' : '' ?>"
                                        id="program"
                                        name="program"
                                        value="<?= $old('program') ?>"
                                        placeholder="e.g. BSc Information Technology"
                                        required
                                    >
                                    <?php if (isset($errors['program'])): ?>
                                        <div class="invalid-feedback"><?= htmlspecialchars($errors['program']) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="year_level" class="form-label">Year Level</label>
                                    <select
                                        class="form-select <?= isset($errors['year_level']) ? 'is-invalid' : '' ?>"
                                        id="year_level"
                                        name="year_level"
                                        required
                                    >
                                        <option value="">Select year level</option>
                                        <?php for ($year = 1; $year <= 4; $year++): ?>
                                            <option
                                                value="<?= $year ?>"
                                                <?= $formData['year_level'] === (string) $year ? 'selected' : '' ?>
                                            >
                                                Year <?= $year ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                    <?php if (isset($errors['year_level'])): ?>
                                        <div class="invalid-feedback"><?= htmlspecialchars($errors['year_level']) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="academic_year" class="form-label">Academic Year</label>
                                    <input
                                        type="text"
                                        class="form-control <?= isset($errors['academic_year']) ? 'is-invalid' : '' ?>"
                                        id="academic_year"
                                        name="academic_year"
                                        value="<?= $old('academic_year') ?>"
                                        placeholder="2025/2026"
                                        required
                                    >
                                    <?php if (isset($errors['academic_year'])): ?>
                                        <div class="invalid-feedback"><?= htmlspecialchars($errors['academic_year']) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input
                                        type="email"
                                        class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                        id="email"
                                        name="email"
                                        value="<?= $old('email') ?>"
                                        autocomplete="email"
                                        required
                                    >
                                    <?php if (isset($errors['email'])): ?>
                                        <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number <span class="text-muted">(optional)</span></label>
                                    <input
                                        type="tel"
                                        class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                                        id="phone"
                                        name="phone"
                                        value="<?= $old('phone') ?>"
                                        autocomplete="tel"
                                    >
                                    <?php if (isset($errors['phone'])): ?>
                                        <div class="invalid-feedback"><?= htmlspecialchars($errors['phone']) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input
                                        type="password"
                                        class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                                        id="password"
                                        name="password"
                                        minlength="8"
                                        autocomplete="new-password"
                                        required
                                    >
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="invalid-feedback"><?= htmlspecialchars($errors['password']) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <input
                                        type="password"
                                        class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>"
                                        id="confirm_password"
                                        name="confirm_password"
                                        minlength="8"
                                        autocomplete="new-password"
                                        required
                                    >
                                    <?php if (isset($errors['confirm_password'])): ?>
                                        <div class="invalid-feedback"><?= htmlspecialchars($errors['confirm_password']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-nsbt w-100">
                                Create Student Account
                            </button>
                        </form>

                        <p class="text-center mt-4 mb-0 text-muted">
                            Already have an account?
                            <a href="<?= APP_URL ?>/public/login.php">Sign in</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>