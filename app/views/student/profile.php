<?php
declare(strict_types=1);

require __DIR__ . '/../../../includes/header.php';

$phone = !empty($student['phone']) ? $student['phone'] : 'Not provided';
?>

<section class="dashboard-page py-4">
    <div class="container">
        <div class="row g-4">
            <aside class="col-lg-3">
                <?php
                $activePage = 'profile';
                require __DIR__ . '/partials/sidebar.php';
                ?>
            </aside>

            <div class="col-lg-9">
                <div class="mb-4">
                    <p class="text-uppercase small fw-semibold text-primary mb-1">
                        Student Information
                    </p>
                    <h1 class="h2 fw-bold mb-1">My Profile</h1>
                    <p class="text-muted mb-0">
                        Your official academic and contact details.
                    </p>
                </div>

                <div class="card dashboard-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <p class="profile-label mb-1">Full Name</p>
                                <p class="fw-semibold mb-0">
                                    <?= htmlspecialchars($student['full_name']) ?>
                                </p>
                            </div>

                            <div class="col-md-6">
                                <p class="profile-label mb-1">Index Number</p>
                                <p class="fw-semibold mb-0">
                                    <?= htmlspecialchars($student['index_number']) ?>
                                </p>
                            </div>

                            <div class="col-md-6">
                                <p class="profile-label mb-1">Programme</p>
                                <p class="fw-semibold mb-0">
                                    <?= htmlspecialchars($student['program']) ?>
                                </p>
                            </div>

                            <div class="col-md-6">
                                <p class="profile-label mb-1">Year Level</p>
                                <p class="fw-semibold mb-0">
                                    Year <?= htmlspecialchars((string) $student['year_level']) ?>
                                </p>
                            </div>

                            <div class="col-md-6">
                                <p class="profile-label mb-1">Academic Year</p>
                                <p class="fw-semibold mb-0">
                                    <?= htmlspecialchars($student['academic_year']) ?>
                                </p>
                            </div>

                            <div class="col-md-6">
                                <p class="profile-label mb-1">Email Address</p>
                                <p class="fw-semibold mb-0">
                                    <?= htmlspecialchars($student['email']) ?>
                                </p>
                            </div>

                            <div class="col-md-6">
                                <p class="profile-label mb-1">Phone Number</p>
                                <p class="fw-semibold mb-0">
                                    <?= htmlspecialchars($phone) ?>
                                </p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <p class="small text-muted mb-0">
                            Contact the academic office if any official academic detail is incorrect.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>