<?php
declare(strict_types=1);

require __DIR__ . '/../../../includes/header.php';
?>

<section class="dashboard-page py-4">
    <div class="container">
        <div class="row g-4">
            <aside class="col-lg-3">
                <?php
                $activePage = 'results';
                require __DIR__ . '/partials/sidebar.php';
                ?>
            </aside>

            <div class="col-lg-9">
                <div class="mb-4">
                    <p class="text-uppercase small fw-semibold text-primary mb-1">
                        Assessment Management
                    </p>
                    <h1 class="h2 fw-bold mb-1">Upload Results</h1>
                    <p class="text-muted mb-0">
                        Enter scores for students registered in your assigned course.
                    </p>
                </div>

                <?php if ($feedback !== ''): ?>
                    <div class="alert alert-<?= htmlspecialchars($feedbackType) ?>" role="alert">
                        <?= htmlspecialchars($feedback) ?>
                    </div>
                <?php endif; ?>

                <div class="card dashboard-card mb-4">
                    <div class="card-body p-4">
                        <form method="GET" action="<?= APP_URL ?>/public/lecturer/results.php">
                            <label for="course_id" class="form-label fw-semibold">
                                Select an Assigned Course
                            </label>

                            <div class="row g-2">
                                <div class="col-md-9">
                                    <select
                                        class="form-select"
                                        id="course_id"
                                        name="course_id"
                                        required
                                    >
                                        <option value="">Choose a course</option>

                                        <?php foreach ($courses as $course): ?>
                                            <option
                                                value="<?= htmlspecialchars((string) $course['course_id']) ?>"
                                                <?= $courseId === (int) $course['course_id'] ? 'selected' : '' ?>
                                            >
                                                <?= htmlspecialchars($course['course_code']) ?>
                                                —
                                                <?= htmlspecialchars($course['course_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-nsbt w-100">
                                        Load Students
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if ($selectedCourse !== null): ?>
                    <div class="card dashboard-card">
                        <div class="card-body p-0">
                            <div class="p-4 border-bottom">
                                <h2 class="h5 fw-bold mb-1">
                                    <?= htmlspecialchars($selectedCourse['course_code']) ?>
                                    —
                                    <?= htmlspecialchars($selectedCourse['course_name']) ?>
                                </h2>

                                <p class="text-muted mb-0">
                                    Enter a score from 0 to 100. The grade is calculated automatically when saved.
                                </p>
                            </div>

                            <?php if ($students === []): ?>
                                <div class="p-4">
                                    <p class="text-muted mb-0">
                                        No students are registered for this course.
                                    </p>
                                </div>
                            <?php else: ?>
                                <form method="POST" action="<?= APP_URL ?>/public/lecturer/results.php">
                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= htmlspecialchars(AuthController::csrfToken()) ?>"
                                    >
                                    <input
                                        type="hidden"
                                        name="course_id"
                                        value="<?= htmlspecialchars((string) $selectedCourse['course_id']) ?>"
                                    >

                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-4">Student</th>
                                                    <th>Index Number</th>
                                                    <th>Current Grade</th>
                                                    <th class="pe-4">Score</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($students as $student): ?>
                                                    <?php
                                                    $studentId = (int) $student['student_id'];

                                                    $scoreValue = $enteredScores[$studentId]
                                                        ?? $student['score']
                                                        ?? '';
                                                    ?>

                                                    <tr>
                                                        <td class="ps-4 fw-semibold">
                                                            <?= htmlspecialchars($student['full_name']) ?>
                                                        </td>

                                                        <td>
                                                            <?= htmlspecialchars($student['index_number']) ?>
                                                        </td>

                                                        <td>
                                                            <?php if ($student['grade'] !== null): ?>
                                                                <span class="badge text-bg-primary">
                                                                    <?= htmlspecialchars($student['grade']) ?>
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="text-muted">Not yet graded</span>
                                                            <?php endif; ?>
                                                        </td>

                                                        <td class="pe-4">
                                                            <input
                                                                type="number"
                                                                class="form-control"
                                                                name="scores[<?= $studentId ?>]"
                                                                value="<?= htmlspecialchars((string) $scoreValue) ?>"
                                                                min="0"
                                                                max="100"
                                                                step="0.01"
                                                                inputmode="decimal"
                                                                aria-label="Score for <?= htmlspecialchars($student['full_name']) ?>"
                                                            >
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="p-4 border-top text-end">
                                        <button type="submit" class="btn btn-nsbt">
                                            Save Results
                                        </button>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>