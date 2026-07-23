<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/models/Student.php';
require_once dirname(__DIR__, 2) . '/app/models/Course.php';
require_once dirname(__DIR__, 2) . '/app/models/Result.php';
require_once dirname(__DIR__, 2) . '/app/models/Document.php';
require_once dirname(__DIR__, 2) . '/app/models/Timetable.php';

class StudentController
{
    private Student $studentModel;
    private Course $courseModel;
    private Result $resultModel;
    private Document $documentModel;
    private Timetable $timetableModel;

    public function __construct()
    {
        $this->studentModel = new Student();
        $this->courseModel = new Course();
        $this->resultModel = new Result();
        $this->documentModel = new Document();
        $this->timetableModel = new Timetable();
    }

    public function getDashboardData(int $userId): array
    {
        return [
            'student' => $this->getProfile($userId),
            'notifications' => $this->studentModel->getRecentNotifications($userId),
            'announcements' => $this->studentModel->getRecentAnnouncements(),
        ];
    }

    public function getProfile(int $userId): array
    {
        $student = $this->studentModel->findByUserId($userId);

        if ($student === null) {
            throw new RuntimeException('Your student profile could not be found.');
        }

        return $student;
    }

    public function getCoursesForUser(int $userId): array
    {
        $student = $this->getProfile($userId);

        return [
            'student' => $student,
            'courses' => $this->courseModel->getCoursesForStudent(
                (int) $student['student_id']
            ),
        ];
    }

    public function getTimetableForUser(int $userId): array
    {
        $student = $this->getProfile($userId);

        return [
            'student' => $student,
            'timetable' => $this->timetableModel->getTimetableForStudent(
                (int) $student['student_id']
            ),
        ];
    }

    public function getResultsForUser(int $userId): array
    {
        $student = $this->getProfile($userId);

        $results = $this->resultModel->getResultsForStudent(
            (int) $student['student_id']
        );

        $totalQualityPoints = 0.0;
        $totalCreditHours = 0;
        $semesterTotals = [];

        foreach ($results as &$result) {
            $gradePoint = $this->getGradePoint($result['grade']);
            $creditHours = (int) $result['credit_hours'];
            $semester = (int) $result['semester'];

            $result['grade_point'] = $gradePoint;

            $totalQualityPoints += $gradePoint * $creditHours;
            $totalCreditHours += $creditHours;

            if (!isset($semesterTotals[$semester])) {
                $semesterTotals[$semester] = [
                    'quality_points' => 0.0,
                    'credit_hours' => 0,
                ];
            }

            $semesterTotals[$semester]['quality_points'] += $gradePoint * $creditHours;
            $semesterTotals[$semester]['credit_hours'] += $creditHours;
        }

        unset($result);

        $semesterGpas = [];

        foreach ($semesterTotals as $semester => $totals) {
            $semesterGpas[$semester] = $totals['credit_hours'] > 0
                ? round($totals['quality_points'] / $totals['credit_hours'], 2)
                : 0.0;
        }

        ksort($semesterGpas);

        $cgpa = $totalCreditHours > 0
            ? round($totalQualityPoints / $totalCreditHours, 2)
            : 0.0;

        return [
            'student' => $student,
            'results' => $results,
            'semester_gpas' => $semesterGpas,
            'cgpa' => $cgpa,
            'total_credit_hours' => $totalCreditHours,
        ];
    }


    public function getAnnouncementsForUser(int $userId): array
{
    return [
        'student' => $this->getProfile($userId),
        'announcements' => $this->studentModel->getAllAnnouncements(),
        'calendar' => $this->studentModel->getLatestAcademicCalendar(),
    ];
}

public function getNotificationsForUser(int $userId): array
{
    return [
        'student' => $this->getProfile($userId),
        'notifications' => $this->studentModel->getAllNotifications($userId),
    ];
}

public function markNotificationAsRead(
    int $notificationId,
    int $userId
): void {
    $this->studentModel->markNotificationAsRead($notificationId, $userId);
}


public function getDownloadsForUser(int $userId): array
{
    return [
        'student' => $this->getProfile($userId),
        'documents' => $this->documentModel->getAvailableDownloads(),
    ];
}

    private function getGradePoint(string $grade): float
    {
        $gradePoints = [
            'A' => 4.0,
            'B+' => 3.5,
            'B' => 3.0,
            'C+' => 2.5,
            'C' => 2.0,
            'D+' => 1.5,
            'D' => 1.0,
            'E' => 0.5,
            'F' => 0.0,
        ];

        $normalisedGrade = strtoupper(trim($grade));

        return $gradePoints[$normalisedGrade] ?? 0.0;
    }
}