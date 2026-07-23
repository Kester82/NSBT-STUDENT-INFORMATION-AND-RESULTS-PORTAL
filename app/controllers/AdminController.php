<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/models/Admin.php';
require_once dirname(__DIR__, 2) . '/app/models/Lecturer.php';

class AdminController
{
    private Admin $adminModel;
    private Lecturer $lecturerModel;

    public function __construct()
    {
        $this->adminModel = new Admin();
        $this->lecturerModel = new Lecturer();
    }

    public function getDashboardData(): array
    {
        return [
            'statistics' => $this->adminModel->getDashboardStatistics(),
        ];
    }

    public function getStudentsData(): array
    {
        return [
            'students' => $this->adminModel->getAllStudents(),
        ];
    }

    public function getLecturersData(): array
    {
        return [
            'lecturers' => $this->adminModel->getAllLecturers(),
        ];
    }

    public function createLecturer(array $data): array
    {
        $fullName = trim((string) ($data['full_name'] ?? ''));
        $email = strtolower(trim((string) ($data['email'] ?? '')));
        $username = trim((string) ($data['username'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $confirmPassword = (string) ($data['confirm_password'] ?? '');

        $errors = [];

        if (mb_strlen($fullName) < 2 || mb_strlen($fullName) > 150) {
            $errors['full_name'] = 'Enter the lecturer’s full name.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter a valid email address.';
        }

        if (!preg_match('/^[A-Za-z0-9._-]{3,50}$/', $username)) {
            $errors['username'] = 'Username must contain 3–50 letters, numbers, dots, hyphens, or underscores.';
        }

        if (strlen($password) < 8) {
            $errors['password'] = 'Password must contain at least 8 characters.';
        }

        if ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'The passwords do not match.';
        }

        if ($errors !== []) {
            return [
                'success' => false,
                'errors' => $errors,
            ];
        }

        try {
            $this->lecturerModel->createAccount([
                'full_name' => $fullName,
                'email' => $email,
                'username' => $username,
                'password' => $password,
            ]);

            return ['success' => true];
        } catch (RuntimeException $exception) {
            return [
                'success' => false,
                'errors' => [
                    'general' => $exception->getMessage(),
                ],
            ];
        }
    }
    public function getCoursesData(): array
{
    return [
        'courses' => $this->adminModel->getAllCourses(),
    ];
}

    public function createCourse(array $data): array
    {
        $courseCode = strtoupper(trim((string) ($data['course_code'] ?? '')));
        $courseName = trim((string) ($data['course_name'] ?? ''));

        $creditHours = filter_var(
            $data['credit_hours'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1, 'max_range' => 12]]
        );

        $semester = filter_var(
            $data['semester'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1, 'max_range' => 2]]
        );

        $errors = [];

        if (!preg_match('/^[A-Z0-9-]{2,20}$/', $courseCode)) {
            $errors['course_code'] = 'Use 2–20 uppercase letters, numbers, or hyphens.';
        }

        if (mb_strlen($courseName) < 2 || mb_strlen($courseName) > 150) {
            $errors['course_name'] = 'Enter a valid course title.';
        }

        if ($creditHours === false) {
            $errors['credit_hours'] = 'Credit hours must be between 1 and 12.';
        }

        if ($semester === false) {
            $errors['semester'] = 'Choose Semester 1 or Semester 2.';
        }

        if ($errors !== []) {
            return [
                'success' => false,
                'errors' => $errors,
            ];
        }

        try {
            $this->adminModel->createCourse([
                'course_code' => $courseCode,
                'course_name' => $courseName,
                'credit_hours' => $creditHours,
                'semester' => $semester,
            ]);

            return ['success' => true];
        } catch (RuntimeException $exception) {
            return [
                'success' => false,
                'errors' => [
                    'general' => $exception->getMessage(),
                ],
            ];
        }
    }
    public function getCourseAssignmentsData(): array
{
    return [
        'lecturers' => $this->adminModel->getLecturersForSelection(),
        'courses' => $this->adminModel->getCoursesForSelection(),
        'assignments' => $this->adminModel->getLecturerCourseAssignments(),
    ];
}

public function assignCourseToLecturer(array $data): array
{
    $lecturerId = filter_var(
        $data['lecturer_id'] ?? null,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    $courseId = filter_var(
        $data['course_id'] ?? null,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    if ($lecturerId === false || $courseId === false) {
        return [
            'success' => false,
            'message' => 'Choose a valid lecturer and course.',
        ];
    }

    try {
        $this->adminModel->assignCourseToLecturer($lecturerId, $courseId);

        return ['success' => true];
    } catch (RuntimeException $exception) {
        return [
            'success' => false,
            'message' => $exception->getMessage(),
        ];
    }
}

    public function removeCourseAssignment(array $data): void
    {
        $lecturerId = filter_var(
            $data['lecturer_id'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        $courseId = filter_var(
            $data['course_id'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        if ($lecturerId === false || $courseId === false) {
            throw new RuntimeException('Invalid course assignment.');
        }

        $this->adminModel->removeCourseAssignment($lecturerId, $courseId);
    }
        public function getAnnouncementsData(): array
    {
        return [
            'announcements' => $this->adminModel->getAllAnnouncements(),
        ];
    }

    public function createAnnouncement(array $data): array
    {
        $title = trim((string) ($data['title'] ?? ''));
        $message = trim((string) ($data['message'] ?? ''));

        $errors = [];

        if (mb_strlen($title) < 2 || mb_strlen($title) > 200) {
            $errors['title'] = 'Title must contain 2–200 characters.';
        }

        if (mb_strlen($message) < 2) {
            $errors['message'] = 'Enter the announcement message.';
        }

        if ($errors !== []) {
            return [
                'success' => false,
                'errors' => $errors,
            ];
        }

        $this->adminModel->createAnnouncement($title, $message);

        return ['success' => true];
    }

    public function deleteAnnouncement(int $announcementId): void
    {
        if ($announcementId < 1) {
            throw new RuntimeException('Invalid announcement.');
        }

        $this->adminModel->deleteAnnouncement($announcementId);
    }
        public function getNotificationsData(): array
    {
        return [
            'students' => $this->adminModel->getStudentsForNotification(),
            'notifications' => $this->adminModel->getAllNotifications(),
        ];
    }

    public function createNotification(array $data): array
    {
        $userId = filter_var(
            $data['user_id'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        $message = trim((string) ($data['message'] ?? ''));

        $errors = [];

        if ($userId === false) {
            $errors['user_id'] = 'Choose a student.';
        }

        if (mb_strlen($message) < 2) {
            $errors['message'] = 'Enter a notification message.';
        }

        if ($errors !== []) {
            return [
                'success' => false,
                'errors' => $errors,
            ];
        }

        $this->adminModel->createNotification($userId, $message);

        return ['success' => true];
    }

    public function deleteNotification(int $notificationId): void
    {
        if ($notificationId < 1) {
            throw new RuntimeException('Invalid notification.');
        }

        $this->adminModel->deleteNotification($notificationId);
    }
    public function getTimetableData(): array
{
    return [
        'courses' => $this->adminModel->getCoursesForSelection(),
        'entries' => $this->adminModel->getAllTimetableEntries(),
    ];
}

public function createTimetableEntry(array $data): array
{
    $courseId = filter_var(
        $data['course_id'] ?? null,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    $day = trim((string) ($data['day'] ?? ''));
    $time = trim((string) ($data['time'] ?? ''));
    $room = trim((string) ($data['room'] ?? ''));

    $allowedDays = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday',
    ];

    $errors = [];

    if ($courseId === false) {
        $errors['course_id'] = 'Choose a course.';
    }

    if (!in_array($day, $allowedDays, true)) {
        $errors['day'] = 'Choose a valid day.';
    }

    $timeObject = DateTime::createFromFormat('H:i', $time);

    if (
        $timeObject === false
        || $timeObject->format('H:i') !== $time
    ) {
        $errors['time'] = 'Choose a valid class time.';
    }

    if (mb_strlen($room) < 1 || mb_strlen($room) > 50) {
        $errors['room'] = 'Enter a room name of up to 50 characters.';
    }

    if ($errors !== []) {
        return [
            'success' => false,
            'errors' => $errors,
        ];
    }

    try {
        $this->adminModel->createTimetableEntry([
            'course_id' => $courseId,
            'day' => $day,
            'time' => $time . ':00',
            'room' => $room,
        ]);

        return ['success' => true];
    } catch (RuntimeException $exception) {
        return [
            'success' => false,
            'errors' => [
                'general' => $exception->getMessage(),
            ],
        ];
    }
}

public function deleteTimetableEntry(int $timetableId): void
{
    if ($timetableId < 1) {
        throw new RuntimeException('Invalid timetable entry.');
    }

    $this->adminModel->deleteTimetableEntry($timetableId);
}
public function getResultsData(?int $courseId): array
{
    $courses = $this->adminModel->getCoursesForSelection();
    $selectedCourse = null;
    $students = [];

    if ($courseId !== null) {
        foreach ($courses as $course) {
            if ((int) $course['course_id'] === $courseId) {
                $selectedCourse = $course;
                break;
            }
        }

        if ($selectedCourse === null) {
            throw new RuntimeException('The selected course does not exist.');
        }

        $students = $this->adminModel->getStudentsForResultsCourse($courseId);
    }

    return [
        'courses' => $courses,
        'selected_course' => $selectedCourse,
        'students' => $students,
    ];
}

public function saveResultScores(int $courseId, array $submittedScores): int
{
    if (!$this->adminModel->courseExists($courseId)) {
        throw new RuntimeException('The selected course does not exist.');
    }

    $results = [];

    foreach ($submittedScores as $studentId => $score) {
        $studentId = (int) $studentId;
        $score = trim((string) $score);

        if ($score === '') {
            continue;
        }

        if (!is_numeric($score)) {
            throw new RuntimeException('Every entered score must be a number.');
        }

        $numericScore = round((float) $score, 2);

        if ($studentId < 1 || $numericScore < 0 || $numericScore > 100) {
            throw new RuntimeException('Scores must be between 0 and 100.');
        }

        $results[] = [
            'student_id' => $studentId,
            'score' => $numericScore,
            'grade' => $this->gradeFromScore($numericScore),
        ];
    }

    if ($results === []) {
        throw new RuntimeException('Enter at least one score before saving.');
    }

    $this->adminModel->saveResultsForCourse($courseId, $results);

    return count($results);
}

private function gradeFromScore(float $score): string
{
    return match (true) {
        $score >= 80 => 'A',
        $score >= 75 => 'B+',
        $score >= 70 => 'B',
        $score >= 65 => 'C+',
        $score >= 60 => 'C',
        $score >= 55 => 'D+',
        $score >= 50 => 'D',
        $score >= 45 => 'E',
        default => 'F',
    };
}
public function getAcademicCalendarData(): array
{
    return [
        'calendar_entries' => $this->adminModel->getAcademicCalendarEntries(),
    ];
}

public function createAcademicCalendarEntry(array $data): array
{
    $reopeningDate = trim((string) ($data['reopening_date'] ?? ''));
    $vacationDate = trim((string) ($data['vacation_date'] ?? ''));

    $reopeningDateObject = DateTime::createFromFormat(
        'Y-m-d',
        $reopeningDate
    );

    $vacationDateObject = DateTime::createFromFormat(
        'Y-m-d',
        $vacationDate
    );

    $errors = [];

    if (
        $reopeningDateObject === false
        || $reopeningDateObject->format('Y-m-d') !== $reopeningDate
    ) {
        $errors['reopening_date'] = 'Choose a valid reopening date.';
    }

    if (
        $vacationDateObject === false
        || $vacationDateObject->format('Y-m-d') !== $vacationDate
    ) {
        $errors['vacation_date'] = 'Choose a valid vacation date.';
    }

    if (
        $errors === []
        && $vacationDate <= $reopeningDate
    ) {
        $errors['vacation_date'] =
            'Vacation date must be after the reopening date.';
    }

    if ($errors !== []) {
        return [
            'success' => false,
            'errors' => $errors,
        ];
    }

    $this->adminModel->createAcademicCalendarEntry(
        $reopeningDate,
        $vacationDate
    );

    return ['success' => true];
}

public function deleteAcademicCalendarEntry(int $calendarId): void
{
    if ($calendarId < 1) {
        throw new RuntimeException('Invalid academic calendar entry.');
    }

    $this->adminModel->deleteAcademicCalendarEntry($calendarId);
}
public function getReportsData(): array
{
    return [
        'statistics' => $this->adminModel->getDashboardStatistics(),
        'programmes' => $this->adminModel->getProgrammeReport(),
        'grades' => $this->adminModel->getGradeDistribution(),
    ];
}
}