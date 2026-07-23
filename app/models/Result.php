<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/config/database.php';

class Result
{
    private PDO $database;

    public function __construct()
    {
        $this->database = Database::connect();
    }

    public function getResultsForStudent(int $studentId): array
    {
        $sql = 'SELECT
                    r.result_id,
                    r.grade,
                    r.score,
                    c.course_code,
                    c.course_name,
                    c.credit_hours,
                    c.semester
                FROM results AS r
                INNER JOIN courses AS c ON c.course_id = r.course_id
                WHERE r.student_id = :student_id
                ORDER BY c.semester ASC, c.course_code ASC';

        $statement = $this->database->prepare($sql);
        $statement->execute(['student_id' => $studentId]);

        return $statement->fetchAll();
    }
}