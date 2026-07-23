<?php
declare(strict_types=1);

require __DIR__ . '/../../../includes/header.php';
?>

<section class="dashboard-page py-4">
    <div class="container">
        <div class="row g-4">
            <aside class="col-lg-3">
                <?php
                $activePage = 'timetable';
                require __DIR__ . '/partials/sidebar.php';
                ?>
            </aside>

            <div class="col-lg-9">
                <div class="mb-4">
                    <p class="text-uppercase small fw-semibold text-primary mb-1">
                        Academic Information
                    </p>
                    <h1 class="h2 fw-bold mb-1">My Timetable</h1>
                    <p class="text-muted mb-0">
                        Your registered course schedule.
                    </p>
                </div>

                <div class="card dashboard-card">
                    <div class="card-body p-0">
                        <?php if ($timetable === []): ?>
                            <div class="p-4">
                                <p class="text-muted mb-0">
                                    No timetable entries are available for your registered courses.
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Day</th>
                                            <th>Time</th>
                                            <th>Course</th>
                                            <th class="pe-4">Room</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($timetable as $class): ?>
                                            <tr>
                                                <td class="ps-4 fw-semibold">
                                                    <?= htmlspecialchars($class['day']) ?>
                                                </td>

                                                <td>
                                                    <?= date('g:i A', strtotime($class['time'])) ?>
                                                </td>

                                                <td>
                                                    <span class="fw-semibold">
                                                        <?= htmlspecialchars($class['course_code']) ?>
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?= htmlspecialchars($class['course_name']) ?>
                                                    </small>
                                                </td>

                                                <td class="pe-4">
                                                    <?= htmlspecialchars($class['room']) ?>
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