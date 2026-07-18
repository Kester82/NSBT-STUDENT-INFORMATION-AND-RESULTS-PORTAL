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
                            <h1 class="h3 fw-bold">Welcome Back</h1>
                            <p class="text-muted mb-0">
                                Sign in to your NSBT Student Portal account.
                            </p>
                        </div>

                        <?php if ($success !== ''): ?>
    <div class="alert alert-success" role="alert">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>


                        <?php if ($error !== ''): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?= APP_URL ?>/public/login.php">
                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= htmlspecialchars(AuthController::csrfToken()) ?>"
                            >

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="username"
                                    name="username"
                                    value="<?= htmlspecialchars($username) ?>"
                                    autocomplete="username"
                                    required
                                    autofocus
                                >
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password"
                                    name="password"
                                    autocomplete="current-password"
                                    required
                                >
                            </div>

                            <button type="submit" class="btn btn-nsbt w-100">
                                Sign In
                            </button>
                        </form>

                        <p class="text-center mt-4 mb-0 text-muted">
                            New student?
                            <a href="<?= APP_URL ?>/public/register.php">Create an account</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>