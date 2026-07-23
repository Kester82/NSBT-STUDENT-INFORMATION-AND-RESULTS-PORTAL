<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/config/database.php';

class Lecturer
{
    private PDO $database;

    public function __construct()
    {
        $this->database = Database::connect();
    }

    public function createAccount(array $lecturerData): int
    {
        $hashedPassword = password_hash(
            $lecturerData['password'],
            PASSWORD_DEFAULT
        );

        try {
            $this->database->beginTransaction();

            $userSql = 'INSERT INTO users (username, password, role)
                        VALUES (:username, :password, :role)';

            $userStatement = $this->database->prepare($userSql);
            $userStatement->execute([
                'username' => $lecturerData['username'],
                'password' => $hashedPassword,
                'role' => 'lecturer',
            ]);

            $userId = (int) $this->database->lastInsertId();

            $lecturerSql = 'INSERT INTO lecturers (user_id, full_name, email)
                            VALUES (:user_id, :full_name, :email)';

            $lecturerStatement = $this->database->prepare($lecturerSql);
            $lecturerStatement->execute([
                'user_id' => $userId,
                'full_name' => $lecturerData['full_name'],
                'email' => $lecturerData['email'],
            ]);

            $this->database->commit();

            return $userId;
        } catch (PDOException $exception) {
            if ($this->database->inTransaction()) {
                $this->database->rollBack();
            }

            if ($exception->getCode() === '23000') {
                throw new RuntimeException(
                    'That username or email address is already in use.'
                );
            }

            error_log('Lecturer account creation failed: ' . $exception->getMessage());

            throw new RuntimeException(
                'Unable to create the lecturer account.'
            );
        }
    }

    public function findByUserId(int $userId): ?array
    {
        $sql = 'SELECT lecturer_id, user_id, full_name, email
                FROM lecturers
                WHERE user_id = :user_id
                LIMIT 1';

        $statement = $this->database->prepare($sql);
        $statement->execute(['user_id' => $userId]);

        $lecturer = $statement->fetch();

        return $lecturer === false ? null : $lecturer;
    }

    public function getAssignedCourses(int $lecturerId): array
    {
    $sql = 'SELECT
                c.course_id,
                c.course_code,
                c.course_name,
                c.credit_hours,
                c.semester,
                COUNT(sc.student_id) AS student_count
            FROM lecturer_courses AS lc
            INNER JOIN courses AS c ON c.course_id = lc.course_id
            LEFT JOIN student_courses AS sc ON sc.course_id = c.course_id
            WHERE lc.lecturer_id = :lecturer_id
            GROUP BY
                c.course_id,
                c.course_code,
                c.course_name,
                c.credit_hours,
                c.semester
            ORDER BY c.semester ASC, c.course_code ASC';

    $statement = $this->database->prepare($sql);
    $statement->execute(['lecturer_id' => $lecturerId]);

    return $statement->fetchAll();
    }

    public function getAssignedCourse(
    int $lecturerId,
    int $courseId
): ?array {
    $sql = 'SELECT
                c.course_id,
                c.course_code,
                c.course_name,
                c.credit_hours,
                c.semester
            FROM lecturer_courses AS lc
            INNER JOIN courses AS c ON c.course_id = lc.course_id
            WHERE lc.lecturer_id = :lecturer_id
              AND c.course_id = :course_id
            LIMIT 1';

    $statement = $this->database->prepare($sql);
    $statement->execute([
        'lecturer_id' => $lecturerId,
        'course_id' => $courseId,
    ]);

    $course = $statement->fetch();

    return $course === false ? null : $course;
}

public function getStudentsForCourse(int $courseId): array
{
    $sql = 'SELECT
                s.student_id,
                s.full_name,
                s.index_number,
                r.score,
                r.grade
            FROM student_courses AS sc
            INNER JOIN students AS s ON s.student_id = sc.student_id
            LEFT JOIN results AS r
                ON r.student_id = s.student_id
               AND r.course_id = sc.course_id
            WHERE sc.course_id = :course_id
            ORDER BY s.full_name ASC';

    $statement = $this->database->prepare($sql);
    $statement->execute(['course_id' => $courseId]);

    return $statement->fetchAll();
}

public function saveCourseResults(
    int $lecturerId,
    int $courseId,
    array $results
): void {
    try {
        $this->database->beginTransaction();

        $course = $this->getAssignedCourse($lecturerId, $courseId);

        if ($course === null) {
            throw new RuntimeException(
                'You are not assigned to this course.'
            );
        }

        $enrolledStudentsSql = 'SELECT student_id
                                FROM student_courses
                                WHERE course_id = :course_id';

        $enrolledStudentsStatement = $this->database->prepare(
            $enrolledStudentsSql
        );
        $enrolledStudentsStatement->execute(['course_id' => $courseId]);

        $enrolledStudentIds = array_flip(
            array_map(
                static fn (array $student): int => (int) $student['student_id'],
                $enrolledStudentsStatement->fetchAll()
            )
        );

        $saveResultSql = 'INSERT INTO results (
                                student_id,
                                course_id,
                                grade,
                                score
                            ) VALUES (
                                :student_id,
                                :course_id,
                                :grade,
                                :score
                            )
                            ON DUPLICATE KEY UPDATE
                                grade = VALUES(grade),
                                score = VALUES(score)';

        $saveResultStatement = $this->database->prepare($saveResultSql);

        foreach ($results as $result) {
            $studentId = (int) $result['student_id'];

            if (!isset($enrolledStudentIds[$studentId])) {
                throw new RuntimeException(
                    'One or more students are not enrolled in this course.'
                );
            }

            $saveResultStatement->execute([
                'student_id' => $studentId,
                'course_id' => $courseId,
                'grade' => $result['grade'],
                'score' => $result['score'],
            ]);
        }

        $this->database->commit();
    } catch (Throwable $exception) {
        if ($this->database->inTransaction()) {
            $this->database->rollBack();
        }

        if ($exception instanceof RuntimeException) {
            throw $exception;
        }

        error_log('Lecturer result save failed: ' . $exception->getMessage());

        throw new RuntimeException(
            'Unable to save the results. Please try again.'
        );
    }
    }
}