<?php
declare(strict_types=1);

require __DIR__ . '/../../../includes/header.php';
?>

<section class="auth-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="card auth-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h1 class="h3 fw-bold">Reset Password</h1>
                            <p class="text-muted mb-0">
                                Choose a new secure password for your account.
                            </p>
                        </div>

                        <?php if ($resetSuccessful): ?>
                            <div class="alert alert-success" role="alert">
                                <?= htmlspecialchars($message) ?>
                            </div>

                            <a href="<?= APP_URL ?>/public/login.php" class="btn btn-nsbt w-100">
                                Go to Login
                            </a>

                        <?php elseif (!$tokenIsValid): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars(
                                    $error !== ''
                                        ? $error
                                        : 'This password reset link is invalid, expired, or has already been used.'
                                ) ?>
                            </div>

                            <a href="<?= APP_URL ?>/public/forgot-password.php" class="btn btn-nsbt w-100">
                                Request a New Link
                            </a>

                        <?php else: ?>
                            <?php if ($error !== ''): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= htmlspecialchars($error) ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="<?= APP_URL ?>/public/reset-password.php">
                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= htmlspecialchars(AuthController::csrfToken()) ?>"
                                >

                                <input
                                    type="hidden"
                                    name="token"
                                    value="<?= htmlspecialchars($token) ?>"
                                >

                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="password"
                                        name="password"
                                        autocomplete="new-password"
                                        minlength="8"
                                        required
                                        autofocus
                                    >
                                    <div class="form-text">
                                        Use at least 8 characters.
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="confirm_password" class="form-label">
                                        Confirm New Password
                                    </label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="confirm_password"
                                        name="confirm_password"
                                        autocomplete="new-password"
                                        minlength="8"
                                        required
                                    >
                                </div>

                                <button type="submit" class="btn btn-nsbt w-100">
                                    Reset Password
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>