<?php
declare(strict_types=1);

require __DIR__ . '/../../../includes/header.php';
?>

<section class="dashboard-page py-4">
    <div class="container">
        <div class="row g-4">
            <aside class="col-lg-3">
                <?php
                $activePage = 'notifications';
                require __DIR__ . '/partials/sidebar.php';
                ?>
            </aside>

            <div class="col-lg-9">
                <div class="mb-4">
                    <p class="text-uppercase small fw-semibold text-primary mb-1">
                        Student Updates
                    </p>
                    <h1 class="h2 fw-bold mb-1">Notifications</h1>
                    <p class="text-muted mb-0">
                        Updates sent directly to your portal account.
                    </p>
                </div>

                <?php if ($feedback !== ''): ?>
                    <div class="alert <?= isset($_GET['updated']) ? 'alert-success' : 'alert-danger' ?>" role="alert">
                        <?= htmlspecialchars($feedback) ?>
                    </div>
                <?php endif; ?>

                <?php if ($notifications === []): ?>
                    <div class="card dashboard-card">
                        <div class="card-body p-4">
                            <p class="text-muted mb-0">
                                You have no notifications.
                            </p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="d-grid gap-3">
                        <?php foreach ($notifications as $notification): ?>
                            <article
                                class="card dashboard-card <?= $notification['status'] === 'unread' ? 'border-start border-primary border-4' : '' ?>"
                            >
                                <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between gap-3">
                                    <div>
                                        <span class="badge <?= $notification['status'] === 'unread' ? 'text-bg-primary' : 'text-bg-secondary' ?> mb-2">
                                            <?= htmlspecialchars(ucfirst($notification['status'])) ?>
                                        </span>

                                        <p class="mb-0">
                                            <?= htmlspecialchars($notification['message']) ?>
                                        </p>
                                    </div>

                                    <?php if ($notification['status'] === 'unread'): ?>
                                        <form method="POST" action="<?= APP_URL ?>/public/student/notifications.php">
                                            <input
                                                type="hidden"
                                                name="csrf_token"
                                                value="<?= htmlspecialchars(AuthController::csrfToken()) ?>"
                                            >
                                            <input
                                                type="hidden"
                                                name="notification_id"
                                                value="<?= htmlspecialchars((string) $notification['notification_id']) ?>"
                                            >

                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                Mark as read
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>