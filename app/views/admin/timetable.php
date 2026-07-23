<?php
declare(strict_types=1);

require __DIR__ . '/../../../includes/header.php';

$days = [
    'Monday',
    'Tuesday',
    'Wednesday',
    'Thursday',
    'Friday',
    'Saturday',
    'Sunday',
];
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
                        Administration
                    </p>
                    <h1 class="h2 fw-bold mb-1">Manage Timetable</h1>
                    <p class="text-muted mb-0">
                        Create class schedules for courses.
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
                        <h2 class="h5 fw-bold mb-3">Add Timetable Entry</h2>

                        <form method="POST" action="<?= APP_URL ?>/public/admin/timetable.php">
                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= htmlspecialchars(AuthController::csrfToken()) ?>"
                            >
                            <input type="hidden" name="action" value="create">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="course_id" class="form-label">Course</label>
                                    <select
                                        class="form-select <?= isset($errors['course_id']) ? 'is-invalid' : '' ?>"
                                        id="course_id"
                                        name="course_id"
                                        required
                                    >
                                        <option value="">Choose course</option>

                                        <?php foreach ($courses as $course): ?>
                                            <option
                                                value="<?= htmlspecialchars((string) $course['course_id']) ?>"
                                                <?= $formData['course_id'] === (string) $course['course_id'] ? 'selected' : '' ?>
                                            >
                                                <?= htmlspecialchars($course['course_code']) ?>
                                                -
                                                <?= htmlspecialchars($course['course_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($errors['course_id'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['course_id']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-3">
                                    <label for="day" class="form-label">Day</label>
                                    <select
                                        class="form-select <?= isset($errors['day']) ? 'is-invalid' : '' ?>"
                                        id="day"
                                        name="day"
                                        required
                                    >
                                        <option value="">Choose day</option>

                                        <?php foreach ($days as $day): ?>
                                            <option
                                                value="<?= htmlspecialchars($day) ?>"
                                                <?= $formData['day'] === $day ? 'selected' : '' ?>
                                            >
                                                <?= htmlspecialchars($day) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($errors['day'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['day']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-3">
                                    <label for="time" class="form-label">Time</label>
                                    <input
                                        type="time"
                                        class="form-control <?= isset($errors['time']) ? 'is-invalid' : '' ?>"
                                        id="time"
                                        name="time"
                                        value="<?= htmlspecialchars($formData['time']) ?>"
                                        required
                                    >
                                    <?php if (isset($errors['time'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['time']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-8">
                                    <label for="room" class="form-label">Room</label>
                                    <input
                                        type="text"
                                        class="form-control <?= isset($errors['room']) ? 'is-invalid' : '' ?>"
                                        id="room"
                                        name="room"
                                        value="<?= htmlspecialchars($formData['room']) ?>"
                                        placeholder="e.g. Computer Lab 1"
                                        required
                                    >
                                    <?php if (isset($errors['room'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['room']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-nsbt w-100">
                                        Add Entry
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card dashboard-card">
                    <div class="card-body p-0">
                        <div class="p-4 border-bottom">
                            <h2 class="h5 fw-bold mb-0">Current Timetable</h2>
                        </div>

                        <?php if ($entries === []): ?>
                            <div class="p-4">
                                <p class="text-muted mb-0">
                                    No timetable entries have been created yet.
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
                                            <th>Room</th>
                                            <th class="pe-4 text-end">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($entries as $entry): ?>
                                            <tr>
                                                <td class="ps-4 fw-semibold">
                                                    <?= htmlspecialchars($entry['day']) ?>
                                                </td>
                                                <td>
                                                    <?= date('g:i A', strtotime($entry['time'])) ?>
                                                </td>
                                                <td>
                                                    <?= htmlspecialchars($entry['course_code']) ?>
                                                    -
                                                    <?= htmlspecialchars($entry['course_name']) ?>
                                                </td>
                                                <td><?= htmlspecialchars($entry['room']) ?></td>
                                                <td class="pe-4 text-end">
                                                    <form method="POST" action="<?= APP_URL ?>/public/admin/timetable.php">
                                                        <input
                                                            type="hidden"
                                                            name="csrf_token"
                                                            value="<?= htmlspecialchars(AuthController::csrfToken()) ?>"
                                                        >
                                                        <input type="hidden" name="action" value="delete">
                                                        <input
                                                            type="hidden"
                                                            name="timetable_id"
                                                            value="<?= htmlspecialchars((string) $entry['timetable_id']) ?>"
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