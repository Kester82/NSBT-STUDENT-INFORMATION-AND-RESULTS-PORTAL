<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/config/database.php';

class Course
{
    private PDO $database;

    public function __construct()
    {
        $this->database = Database::connect();
    }

    public function getCoursesForStudent(int $studentId): array
    {
        $sql = 'SELECT
                    c.course_id,
                    c.course_code,
                    c.course_name,
                    c.credit_hours,
                    c.semester
                FROM student_courses AS sc
                INNER JOIN courses AS c ON c.course_id = sc.course_id
                WHERE sc.student_id = :student_id
                ORDER BY c.semester ASC, c.course_code ASC';

        $statement = $this->database->prepare($sql);
        $statement->execute(['student_id' => $studentId]);

        return $statement->fetchAll();
    }
}