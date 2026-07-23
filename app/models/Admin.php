<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/config/database.php';

class Admin
{
    private PDO $database;

    public function __construct()
    {
        $this->database = Database::connect();
    }

    public function getDashboardStatistics(): array
    {
        $sql = 'SELECT
                    (SELECT COUNT(*) FROM students) AS student_count,
                    (SELECT COUNT(*) FROM lecturers) AS lecturer_count,
                    (SELECT COUNT(*) FROM courses) AS course_count,
                    (SELECT COUNT(*) FROM results) AS result_count';

        $statistics = $this->database->query($sql)->fetch();

        return $statistics === false ? [
            'student_count' => 0,
            'lecturer_count' => 0,
            'course_count' => 0,
            'result_count' => 0,
        ] : $statistics;
    }

    public function getAllStudents(): array
    {
        $sql = 'SELECT
                    s.student_id,
                    s.full_name,
                    s.index_number,
                    s.program,
                    s.year_level,
                    s.academic_year,
                    s.email,
                    s.phone,
                    u.username
                FROM students AS s
                INNER JOIN users AS u ON u.user_id = s.user_id
                ORDER BY s.full_name ASC';

        return $this->database->query($sql)->fetchAll();
    }

    public function getAllLecturers(): array
    {
        $sql = 'SELECT
                    l.lecturer_id,
                    l.full_name,
                    l.email,
                    u.username,
                    COUNT(lc.course_id) AS assigned_course_count
                FROM lecturers AS l
                INNER JOIN users AS u ON u.user_id = l.user_id
                LEFT JOIN lecturer_courses AS lc
                    ON lc.lecturer_id = l.lecturer_id
                GROUP BY
                    l.lecturer_id,
                    l.full_name,
                    l.email,
                    u.username
                ORDER BY l.full_name ASC';

        return $this->database->query($sql)->fetchAll();
    }

    public function getAllCourses(): array
{
    $sql = 'SELECT
                c.course_id,
                c.course_code,
                c.course_name,
                c.credit_hours,
                c.semester,
                COUNT(DISTINCT sc.student_id) AS student_count,
                COUNT(DISTINCT lc.lecturer_id) AS lecturer_count
            FROM courses AS c
            LEFT JOIN student_courses AS sc ON sc.course_id = c.course_id
            LEFT JOIN lecturer_courses AS lc ON lc.course_id = c.course_id
            GROUP BY
                c.course_id,
                c.course_code,
                c.course_name,
                c.credit_hours,
                c.semester
            ORDER BY c.semester ASC, c.course_code ASC';

    return $this->database->query($sql)->fetchAll();
}

    public function createCourse(array $courseData): void
    {
        $sql = 'INSERT INTO courses (
                    course_code,
                    course_name,
                    credit_hours,
                    semester
                ) VALUES (
                    :course_code,
                    :course_name,
                    :credit_hours,
                    :semester
                )';

        try {
            $statement = $this->database->prepare($sql);
            $statement->execute([
                'course_code' => $courseData['course_code'],
                'course_name' => $courseData['course_name'],
                'credit_hours' => $courseData['credit_hours'],
                'semester' => $courseData['semester'],
            ]);
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                throw new RuntimeException('That course code already exists.');
            }

            error_log('Course creation failed: ' . $exception->getMessage());

            throw new RuntimeException('Unable to create the course.');
        }
    }
    public function getLecturersForSelection(): array
{
    $sql = 'SELECT lecturer_id, full_name
            FROM lecturers
            ORDER BY full_name ASC';

    return $this->database->query($sql)->fetchAll();
}

public function getCoursesForSelection(): array
{
    $sql = 'SELECT course_id, course_code, course_name
            FROM courses
            ORDER BY semester ASC, course_code ASC';

    return $this->database->query($sql)->fetchAll();
}

    public function getLecturerCourseAssignments(): array
    {
        $sql = 'SELECT
                    lc.lecturer_id,
                    lc.course_id,
                    l.full_name AS lecturer_name,
                    c.course_code,
                    c.course_name
                FROM lecturer_courses AS lc
                INNER JOIN lecturers AS l ON l.lecturer_id = lc.lecturer_id
                INNER JOIN courses AS c ON c.course_id = lc.course_id
                ORDER BY l.full_name ASC, c.course_code ASC';

        return $this->database->query($sql)->fetchAll();
    }

    public function assignCourseToLecturer(
        int $lecturerId,
        int $courseId
    ): void {
        $sql = 'INSERT INTO lecturer_courses (lecturer_id, course_id)
                VALUES (:lecturer_id, :course_id)';

        try {
            $statement = $this->database->prepare($sql);
            $statement->execute([
                'lecturer_id' => $lecturerId,
                'course_id' => $courseId,
            ]);
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                throw new RuntimeException(
                    'This lecturer is already assigned to that course.'
                );
            }

            error_log('Lecturer course assignment failed: ' . $exception->getMessage());

            throw new RuntimeException('Unable to assign the course.');
        }
    }

    public function removeCourseAssignment(
        int $lecturerId,
        int $courseId
    ): void {
        $sql = 'DELETE FROM lecturer_courses
                WHERE lecturer_id = :lecturer_id
                AND course_id = :course_id';

        $statement = $this->database->prepare($sql);
        $statement->execute([
            'lecturer_id' => $lecturerId,
            'course_id' => $courseId,
        ]);
    }
        public function getAllAnnouncements(): array
    {
        $sql = 'SELECT announcement_id, title, message, date_created
                FROM announcements
                ORDER BY date_created DESC';

        return $this->database->query($sql)->fetchAll();
    }

    public function createAnnouncement(string $title, string $message): void
    {
        $sql = 'INSERT INTO announcements (title, message)
                VALUES (:title, :message)';

        $statement = $this->database->prepare($sql);
        $statement->execute([
            'title' => $title,
            'message' => $message,
        ]);
    }

    public function deleteAnnouncement(int $announcementId): void
    {
        $sql = 'DELETE FROM announcements
                WHERE announcement_id = :announcement_id';

        $statement = $this->database->prepare($sql);
        $statement->execute([
            'announcement_id' => $announcementId,
        ]);
    }
        public function getStudentsForNotification(): array
    {
        $sql = 'SELECT
                    s.full_name,
                    s.index_number,
                    s.user_id
                FROM students AS s
                ORDER BY s.full_name ASC';

        return $this->database->query($sql)->fetchAll();
    }

    public function getAllNotifications(): array
    {
        $sql = 'SELECT
                    n.notification_id,
                    n.message,
                    n.status,
                    s.full_name,
                    s.index_number
                FROM notifications AS n
                INNER JOIN students AS s ON s.user_id = n.user_id
                ORDER BY n.notification_id DESC';

        return $this->database->query($sql)->fetchAll();
    }

    public function createNotification(int $userId, string $message): void
    {
        $sql = 'INSERT INTO notifications (user_id, message, status)
                VALUES (:user_id, :message, :status)';

        $statement = $this->database->prepare($sql);
        $statement->execute([
            'user_id' => $userId,
            'message' => $message,
            'status' => 'unread',
        ]);
    }

    public function deleteNotification(int $notificationId): void
    {
        $sql = 'DELETE FROM notifications
                WHERE notification_id = :notification_id';

        $statement = $this->database->prepare($sql);
        $statement->execute([
            'notification_id' => $notificationId,
        ]);
    }
    public function getAllTimetableEntries(): array
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

    return $this->database->query($sql)->fetchAll();
}

public function createTimetableEntry(array $entry): void
{
    $duplicateSql = 'SELECT COUNT(*)
                     FROM timetable
                     WHERE course_id = :course_id
                       AND `day` = :day
                       AND `time` = :time
                       AND room = :room';

    $duplicateStatement = $this->database->prepare($duplicateSql);
    $duplicateStatement->execute([
        'course_id' => $entry['course_id'],
        'day' => $entry['day'],
        'time' => $entry['time'],
        'room' => $entry['room'],
    ]);

    if ((int) $duplicateStatement->fetchColumn() > 0) {
        throw new RuntimeException('This timetable entry already exists.');
    }

    $sql = 'INSERT INTO timetable (course_id, `day`, `time`, room)
            VALUES (:course_id, :day, :time, :room)';

    $statement = $this->database->prepare($sql);
    $statement->execute([
        'course_id' => $entry['course_id'],
        'day' => $entry['day'],
        'time' => $entry['time'],
        'room' => $entry['room'],
    ]);
}

public function deleteTimetableEntry(int $timetableId): void
{
    $sql = 'DELETE FROM timetable
            WHERE timetable_id = :timetable_id';

    $statement = $this->database->prepare($sql);
    $statement->execute([
        'timetable_id' => $timetableId,
    ]);
}
public function courseExists(int $courseId): bool
{
    $sql = 'SELECT COUNT(*)
            FROM courses
            WHERE course_id = :course_id';

    $statement = $this->database->prepare($sql);
    $statement->execute(['course_id' => $courseId]);

    return (int) $statement->fetchColumn() > 0;
}

public function getStudentsForResultsCourse(int $courseId): array
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

public function saveResultsForCourse(int $courseId, array $results): void
{
    try {
        $this->database->beginTransaction();

        $enrolledSql = 'SELECT student_id
                        FROM student_courses
                        WHERE course_id = :course_id';

        $enrolledStatement = $this->database->prepare($enrolledSql);
        $enrolledStatement->execute(['course_id' => $courseId]);

        $enrolledStudentIds = array_flip(
            array_map(
                static fn (array $student): int => (int) $student['student_id'],
                $enrolledStatement->fetchAll()
            )
        );

        $saveSql = 'INSERT INTO results (
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

        $saveStatement = $this->database->prepare($saveSql);

        foreach ($results as $result) {
            $studentId = (int) $result['student_id'];

            if (!isset($enrolledStudentIds[$studentId])) {
                throw new RuntimeException(
                    'One or more students are not enrolled in this course.'
                );
            }

            $saveStatement->execute([
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

        error_log('Admin result save failed: ' . $exception->getMessage());

        throw new RuntimeException('Unable to save results.');
    }
}
public function getAcademicCalendarEntries(): array
{
    $sql = 'SELECT id, reopening_date, vacation_date
            FROM academic_calendar
            ORDER BY id DESC';

    return $this->database->query($sql)->fetchAll();
}

public function createAcademicCalendarEntry(
    string $reopeningDate,
    string $vacationDate
): void {
    $sql = 'INSERT INTO academic_calendar (
                reopening_date,
                vacation_date
            ) VALUES (
                :reopening_date,
                :vacation_date
            )';

    $statement = $this->database->prepare($sql);
    $statement->execute([
        'reopening_date' => $reopeningDate,
        'vacation_date' => $vacationDate,
    ]);
}

public function deleteAcademicCalendarEntry(int $calendarId): void
{
    $sql = 'DELETE FROM academic_calendar
            WHERE id = :calendar_id';

    $statement = $this->database->prepare($sql);
    $statement->execute([
        'calendar_id' => $calendarId,
    ]);
}
public function getProgrammeReport(): array
{
    $sql = 'SELECT
                program,
                COUNT(*) AS student_count
            FROM students
            GROUP BY program
            ORDER BY student_count DESC, program ASC';

    return $this->database->query($sql)->fetchAll();
}

public function getGradeDistribution(): array
{
    $sql = 'SELECT
                grade,
                COUNT(*) AS result_count
            FROM results
            GROUP BY grade
            ORDER BY
                FIELD(
                    grade,
                    "A",
                    "B+",
                    "B",
                    "C+",
                    "C",
                    "D+",
                    "D",
                    "E",
                    "F"
                )';

    return $this->database->query($sql)->fetchAll();
}
}