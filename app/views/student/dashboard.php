<?php
declare(strict_types=1);

require __DIR__ . '/../../../includes/header.php';
?>

<section class="dashboard-page py-4">
    <div class="container">
        <div class="row g-4">
            <aside class="col-lg-3">
                <?php
                $activePage = 'dashboard';
                require __DIR__ . '/partials/sidebar.php';
                ?>
            </aside>

            <div class="col-lg-9">
                <div class="dashboard-welcome mb-4">
                    <p class="text-uppercase small fw-semibold mb-1">Student Portal</p>
                    <h1 class="h2 fw-bold mb-2">
                        Welcome, <?= htmlspecialchars($student['full_name']) ?>
                    </h1>
                    <p class="mb-0">
                        Here is a quick overview of your academic information.
                    </p>
                </div>

                <div class="card dashboard-card mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="h5 fw-bold mb-0">Profile Summary</h2>
                            <span class="badge text-bg-primary">
                                Year <?= htmlspecialchars((string) $student['year_level']) ?>
                            </span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="profile-label mb-1">Index Number</p>
                                <p class="mb-0 fw-semibold">
                                    <?= htmlspecialchars($student['index_number']) ?>
                                </p>
                            </div>

                            <div class="col-md-6">
                                <p class="profile-label mb-1">Programme</p>
                                <p class="mb-0 fw-semibold">
                                    <?= htmlspecialchars($student['program']) ?>
                                </p>
                            </div>

                            <div class="col-md-6">
                                <p class="profile-label mb-1">Academic Year</p>
                                <p class="mb-0 fw-semibold">
                                    <?= htmlspecialchars($student['academic_year']) ?>
                                </p>
                            </div>

                            <div class="col-md-6">
                                <p class="profile-label mb-1">Email Address</p>
                                <p class="mb-0 fw-semibold">
                                    <?= htmlspecialchars($student['email']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <h2 class="h5 fw-bold mb-3">Notifications</h2>

                                <?php if ($notifications === []): ?>
                                    <p class="text-muted mb-0">
                                        You have no notifications yet.
                                    </p>
                                <?php else: ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($notifications as $notification): ?>
                                            <div class="list-group-item px-0">
                                                <p class="mb-1">
                                                    <?= htmlspecialchars($notification['message']) ?>
                                                </p>
                                                <small class="text-muted text-capitalize">
                                                    <?= htmlspecialchars($notification['status']) ?>
                                                </small>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <h2 class="h5 fw-bold mb-3">Latest Announcements</h2>

                                <?php if ($announcements === []): ?>
                                    <p class="text-muted mb-0">
                                        No announcements have been posted yet.
                                    </p>
                                <?php else: ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($announcements as $announcement): ?>
                                            <div class="list-group-item px-0">
                                                <h3 class="h6 fw-bold">
                                                    <?= htmlspecialchars($announcement['title']) ?>
                                                </h3>

                                                <p class="mb-1 small">
                                                    <?= nl2br(htmlspecialchars($announcement['message'])) ?>
                                                </p>

                                                <small class="text-muted">
                                                    <?= date('d M Y', strtotime($announcement['date_created'])) ?>
                                                </small>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>