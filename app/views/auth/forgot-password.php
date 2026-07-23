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
                            <h1 class="h3 fw-bold">Forgot Password?</h1>
                            <p class="text-muted mb-0">
                                Enter your registered email address to receive a reset link.
                            </p>
                        </div>

                        <?php if ($message !== ''): ?>
                            <div class="alert alert-success" role="alert">
                                <?= htmlspecialchars($message) ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($error !== ''): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?= APP_URL ?>/public/forgot-password.php">
                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= htmlspecialchars(AuthController::csrfToken()) ?>"
                            >

                            <div class="mb-4">
                                <label for="email" class="form-label">Email Address</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    value="<?= htmlspecialchars($email) ?>"
                                    autocomplete="email"
                                    required
                                    autofocus
                                >
                            </div>

                            <button type="submit" class="btn btn-nsbt w-100">
                                Send Reset Link
                            </button>
                        </form>

                        <p class="text-center mt-4 mb-0 text-muted">
                            Remembered your password?
                            <a href="<?= APP_URL ?>/public/login.php">Back to login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>