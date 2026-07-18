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
                        NSBT Updates
                    </p>
                    <h1 class="h2 fw-bold mb-1">Announcements</h1>
                    <p class="text-muted mb-0">
                        Important academic and campus information.
                    </p>
                </div>

                <?php if ($calendar !== null): ?>
                    <div class="card dashboard-card mb-4">
                        <div class="card-body p-4">
                            <h2 class="h5 fw-bold mb-3">Academic Calendar</h2>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <p class="profile-label mb-1">Reopening Date</p>
                                    <p class="fw-semibold mb-0">
                                        <?= date('d M Y', strtotime($calendar['reopening_date'])) ?>
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <p class="profile-label mb-1">Vacation Date</p>
                                    <p class="fw-semibold mb-0">
                                        <?= date('d M Y', strtotime($calendar['vacation_date'])) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($announcements === []): ?>
                    <div class="card dashboard-card">
                        <div class="card-body p-4">
                            <p class="text-muted mb-0">
                                No announcements have been posted yet.
                            </p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="d-grid gap-3">
                        <?php foreach ($announcements as $announcement): ?>
                            <article class="card dashboard-card">
                                <div class="card-body p-4">
                                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-2">
                                        <h2 class="h5 fw-bold mb-0">
                                            <?= htmlspecialchars($announcement['title']) ?>
                                        </h2>

                                        <small class="text-muted">
                                            <?= date('d M Y', strtotime($announcement['date_created'])) ?>
                                        </small>
                                    </div>

                                    <p class="mb-0">
                                        <?= nl2br(htmlspecialchars($announcement['message'])) ?>
                                    </p>
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