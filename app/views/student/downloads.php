<?php
declare(strict_types=1);

require __DIR__ . '/../../../includes/header.php';
?>

<section class="dashboard-page py-4">
    <div class="container">
        <div class="row g-4">
            <aside class="col-lg-3">
                <?php
                $activePage = 'downloads';
                require __DIR__ . '/partials/sidebar.php';
                ?>
            </aside>

            <div class="col-lg-9">
                <div class="mb-4">
                    <p class="text-uppercase small fw-semibold text-primary mb-1">
                        Academic Information
                    </p>
                    <h1 class="h2 fw-bold mb-1">Downloads</h1>
                    <p class="text-muted mb-0">
                        Official documents available to students.
                    </p>
                </div>

                <div class="card dashboard-card">
                    <div class="card-body p-0">
                        <?php if ($documents === []): ?>
                            <div class="p-4">
                                <p class="text-muted mb-0">
                                    No documents are available for download yet.
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($documents as $document): ?>
                                    <div class="list-group-item p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                        <div>
                                            <h2 class="h6 fw-bold mb-1">
                                                <?= htmlspecialchars($document['title']) ?>
                                            </h2>
                                            <small class="text-muted">
                                                PDF document
                                            </small>
                                        </div>

                                        <a
                                            class="btn btn-outline-primary"
                                            href="<?= htmlspecialchars($document['url']) ?>"
                                            download
                                        >
                                            Download PDF
                                        </a>
                                    </div>
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