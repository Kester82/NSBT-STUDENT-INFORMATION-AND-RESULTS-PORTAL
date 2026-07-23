<?php
declare(strict_types=1);

require __DIR__ . '/../../../includes/header.php';
?>

<section class="dashboard-page py-4">
    <div class="container">
        <div class="row g-4">
            <aside class="col-lg-3">
                <?php
                $activePage = 'calendar';
                require __DIR__ . '/partials/sidebar.php';
                ?>
            </aside>

            <div class="col-lg-9">
                <div class="mb-4">
                    <p class="text-uppercase small fw-semibold text-primary mb-1">
                        Administration
                    </p>
                    <h1 class="h2 fw-bold mb-1">Academic Calendar</h1>
                    <p class="text-muted mb-0">
                        Set the reopening and vacation dates students see in the portal.
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
                        <h2 class="h5 fw-bold mb-3">Add Calendar Dates</h2>

                        <form method="POST" action="<?= APP_URL ?>/public/admin/academic-calendar.php">
                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= htmlspecialchars(AuthController::csrfToken()) ?>"
                            >
                            <input type="hidden" name="action" value="create">

                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label for="reopening_date" class="form-label">
                                        Reopening Date
                                    </label>
                                    <input
                                        type="date"
                                        class="form-control <?= isset($errors['reopening_date']) ? 'is-invalid' : '' ?>"
                                        id="reopening_date"
                                        name="reopening_date"
                                        value="<?= htmlspecialchars($formData['reopening_date']) ?>"
                                        required
                                    >
                                    <?php if (isset($errors['reopening_date'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['reopening_date']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-5">
                                    <label for="vacation_date" class="form-label">
                                        Vacation Date
                                    </label>
                                    <input
                                        type="date"
                                        class="form-control <?= isset($errors['vacation_date']) ? 'is-invalid' : '' ?>"
                                        id="vacation_date"
                                        name="vacation_date"
                                        value="<?= htmlspecialchars($formData['vacation_date']) ?>"
                                        required
                                    >
                                    <?php if (isset($errors['vacation_date'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['vacation_date']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-nsbt w-100">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card dashboard-card">
                    <div class="card-body p-0">
                        <div class="p-4 border-bottom">
                            <h2 class="h5 fw-bold mb-0">Calendar History</h2>
                        </div>

                        <?php if ($calendarEntries === []): ?>
                            <div class="p-4">
                                <p class="text-muted mb-0">
                                    No calendar dates have been added yet.
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Reopening Date</th>
                                            <th>Vacation Date</th>
                                            <th class="pe-4 text-end">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($calendarEntries as $entry): ?>
                                            <tr>
                                                <td class="ps-4 fw-semibold">
                                                    <?= date('d M Y', strtotime($entry['reopening_date'])) ?>
                                                </td>
                                                <td>
                                                    <?= date('d M Y', strtotime($entry['vacation_date'])) ?>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <form method="POST" action="<?= APP_URL ?>/public/admin/academic-calendar.php">
                                                        <input
                                                            type="hidden"
                                                            name="csrf_token"
                                                            value="<?= htmlspecialchars(AuthController::csrfToken()) ?>"
                                                        >
                                                        <input type="hidden" name="action" value="delete">
                                                        <input
                                                            type="hidden"
                                                            name="calendar_id"
                                                            value="<?= htmlspecialchars((string) $entry['id']) ?>"
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