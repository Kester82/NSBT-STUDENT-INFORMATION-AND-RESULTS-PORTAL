<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/models/Lecturer.php';

class LecturerController
{
    private Lecturer $lecturerModel;

    public function __construct()
    {
        $this->lecturerModel = new Lecturer();
    }

    public function getDashboardData(int $userId): array
    {
        $lecturer = $this->getLecturerProfile($userId);

        return [
            'lecturer' => $lecturer,
            'courses' => $this->lecturerModel->getAssignedCourses(
                (int) $lecturer['lecturer_id']
            ),
        ];
    }

    public function getResultEntryData(
        int $userId,
        ?int $courseId
    ): array {
        $lecturer = $this->getLecturerProfile($userId);

        $courses = $this->lecturerModel->getAssignedCourses(
            (int) $lecturer['lecturer_id']
        );

        $selectedCourse = null;
        $students = [];

        if ($courseId !== null) {
            $selectedCourse = $this->lecturerModel->getAssignedCourse(
                (int) $lecturer['lecturer_id'],
                $courseId
            );

            if ($selectedCourse === null) {
                throw new RuntimeException(
                    'You are not assigned to the selected course.'
                );
            }

            $students = $this->lecturerModel->getStudentsForCourse($courseId);
        }

        return [
            'lecturer' => $lecturer,
            'courses' => $courses,
            'selected_course' => $selectedCourse,
            'students' => $students,
        ];
    }

    public function saveResultScores(
        int $userId,
        int $courseId,
        array $submittedScores
    ): int {
        $lecturer = $this->getLecturerProfile($userId);
        $results = [];

        foreach ($submittedScores as $studentId => $score) {
            $studentId = (int) $studentId;
            $score = trim((string) $score);

            if ($score === '') {
                continue;
            }

            if (!is_numeric($score)) {
                throw new RuntimeException(
                    'Every entered score must be a number.'
                );
            }

            $numericScore = round((float) $score, 2);

            if ($studentId < 1 || $numericScore < 0 || $numericScore > 100) {
                throw new RuntimeException(
                    'Scores must be between 0 and 100.'
                );
            }

            $results[] = [
                'student_id' => $studentId,
                'score' => $numericScore,
                'grade' => $this->gradeFromScore($numericScore),
            ];
        }

        if ($results === []) {
            throw new RuntimeException(
                'Enter at least one score before saving.'
            );
        }

        $this->lecturerModel->saveCourseResults(
            (int) $lecturer['lecturer_id'],
            $courseId,
            $results
        );

        return count($results);
    }

    private function getLecturerProfile(int $userId): array
    {
        $lecturer = $this->lecturerModel->findByUserId($userId);

        if ($lecturer === null) {
            throw new RuntimeException(
                'Your lecturer profile could not be found.'
            );
        }

        return $lecturer;
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
}