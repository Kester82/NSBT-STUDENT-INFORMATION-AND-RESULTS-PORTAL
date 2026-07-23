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
                        Administration
                    </p>
                    <h1 class="h2 fw-bold mb-1">Notifications</h1>
                    <p class="text-muted mb-0">
                        Send direct updates to individual student accounts.
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
                        <h2 class="h5 fw-bold mb-3">Send Notification</h2>

                        <form method="POST" action="<?= APP_URL ?>/public/admin/notifications.php">
                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= htmlspecialchars(AuthController::csrfToken()) ?>"
                            >
                            <input type="hidden" name="action" value="create">

                            <div class="mb-3">
                                <label for="user_id" class="form-label">Student</label>
                                <select
                                    class="form-select <?= isset($errors['user_id']) ? 'is-invalid' : '' ?>"
                                    id="user_id"
                                    name="user_id"
                                    required
                                >
                                    <option value="">Choose student</option>

                                    <?php foreach ($students as $student): ?>
                                        <option
                                            value="<?= htmlspecialchars((string) $student['user_id']) ?>"
                                            <?= $formData['user_id'] === (string) $student['user_id'] ? 'selected' : '' ?>
                                        >
                                            <?= htmlspecialchars($student['full_name']) ?>
                                            -
                                            <?= htmlspecialchars($student['index_number']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors['user_id'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['user_id']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea
                                    class="form-control <?= isset($errors['message']) ? 'is-invalid' : '' ?>"
                                    id="message"
                                    name="message"
                                    rows="4"
                                    required
                                ><?= htmlspecialchars($formData['message']) ?></textarea>
                                <?php if (isset($errors['message'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['message']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <button type="submit" class="btn btn-nsbt">
                                Send Notification
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card dashboard-card">
                    <div class="card-body p-0">
                        <div class="p-4 border-bottom">
                            <h2 class="h5 fw-bold mb-0">Sent Notifications</h2>
                        </div>

                        <?php if ($notifications === []): ?>
                            <div class="p-4">
                                <p class="text-muted mb-0">No notifications have been sent yet.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Student</th>
                                            <th>Message</th>
                                            <th>Status</th>
                                            <th class="pe-4 text-end">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($notifications as $notification): ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <span class="fw-semibold">
                                                        <?= htmlspecialchars($notification['full_name']) ?>
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?= htmlspecialchars($notification['index_number']) ?>
                                                    </small>
                                                </td>
                                                <td><?= htmlspecialchars($notification['message']) ?></td>
                                                <td>
                                                    <span class="badge <?= $notification['status'] === 'unread' ? 'text-bg-primary' : 'text-bg-secondary' ?>">
                                                        <?= htmlspecialchars(ucfirst($notification['status'])) ?>
                                                    </span>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <form method="POST" action="<?= APP_URL ?>/public/admin/notifications.php">
                                                        <input
                                                            type="hidden"
                                                            name="csrf_token"
                                                            value="<?= htmlspecialchars(AuthController::csrfToken()) ?>"
                                                        >
                                                        <input type="hidden" name="action" value="delete">
                                                        <input
                                                            type="hidden"
                                                            name="notification_id"
                                                            value="<?= htmlspecialchars((string) $notification['notification_id']) ?>"
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