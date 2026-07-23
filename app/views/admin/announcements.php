<?php
declare(strict_types=1);

require __DIR__ . '/../../../includes/header.php';
?>

<section class="dashboard-page py-4">
    <div class="container">
        <div class="row g-4">
            <aside class="col-lg-3">
                <?php
                $activePage = 'announcements';
                require __DIR__ . '/partials/sidebar.php';
                ?>
            </aside>

            <div class="col-lg-9">
                <div class="mb-4">
                    <p class="text-uppercase small fw-semibold text-primary mb-1">
                        Administration
                    </p>
                    <h1 class="h2 fw-bold mb-1">Announcements</h1>
                    <p class="text-muted mb-0">
                        Publish portal-wide notices for students and staff.
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
                        <h2 class="h5 fw-bold mb-3">Post Announcement</h2>

                        <form method="POST" action="<?= APP_URL ?>/public/admin/announcements.php">
                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= htmlspecialchars(AuthController::csrfToken()) ?>"
                            >
                            <input type="hidden" name="action" value="create">

                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input
                                    type="text"
                                    class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>"
                                    id="title"
                                    name="title"
                                    value="<?= htmlspecialchars($formData['title']) ?>"
                                    required
                                >
                                <?php if (isset($errors['title'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['title']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea
                                    class="form-control <?= isset($errors['message']) ? 'is-invalid' : '' ?>"
                                    id="message"
                                    name="message"
                                    rows="5"
                                    required
                                ><?= htmlspecialchars($formData['message']) ?></textarea>
                                <?php if (isset($errors['message'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['message']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <button type="submit" class="btn btn-nsbt">
                                Post Announcement
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card dashboard-card">
                    <div class="card-body p-0">
                        <div class="p-4 border-bottom">
                            <h2 class="h5 fw-bold mb-0">Published Announcements</h2>
                        </div>

                        <?php if ($announcements === []): ?>
                            <div class="p-4">
                                <p class="text-muted mb-0">
                                    No announcements have been posted yet.
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($announcements as $announcement): ?>
                                    <article class="list-group-item p-4">
                                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                                            <div>
                                                <h3 class="h6 fw-bold">
                                                    <?= htmlspecialchars($announcement['title']) ?>
                                                </h3>

                                                <p class="mb-2">
                                                    <?= nl2br(htmlspecialchars($announcement['message'])) ?>
                                                </p>

                                                <small class="text-muted">
                                                    <?= date('d M Y, g:i A', strtotime($announcement['date_created'])) ?>
                                                </small>
                                            </div>

                                            <form method="POST" action="<?= APP_URL ?>/public/admin/announcements.php">
                                                <input
                                                    type="hidden"
                                                    name="csrf_token"
                                                    value="<?= htmlspecialchars(AuthController::csrfToken()) ?>"
                                                >
                                                <input type="hidden" name="action" value="delete">
                                                <input
                                                    type="hidden"
                                                    name="announcement_id"
                                                    value="<?= htmlspecialchars((string) $announcement['announcement_id']) ?>"
                                                >

                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>