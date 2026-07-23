<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/config/database.php';

class Timetable
{
    private PDO $database;

    public function __construct()
    {
        $this->database = Database::connect();
    }

    public function getTimetableForStudent(int $studentId): array
    {
        $sql = 'SELECT
                    t.timetable_id,
                    t.`day`,
                    t.`time`,
                    t.room,
                    c.course_code,
                    c.course_name
                FROM timetable AS t
                INNER JOIN courses AS c ON c.course_id = t.course_id
                INNER JOIN student_courses AS sc ON sc.course_id = c.course_id
                WHERE sc.student_id = :student_id
                ORDER BY
                    FIELD(
                        t.`day`,
                        "Monday",
                        "Tuesday",
                        "Wednesday",
                        "Thursday",
                        "Friday",
                        "Saturday",
                        "Sunday"
                    ),
                    t.`time` ASC';

        $statement = $this->database->prepare($sql);
        $statement->execute(['student_id' => $studentId]);

        return $statement->fetchAll();
    }
}