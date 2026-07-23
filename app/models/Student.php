<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/config/database.php';

class Student
{
    private PDO $database;

    public function __construct()
    {
        $this->database = Database::connect();
    }


public function getAllNotifications(int $userId): array
{
    $sql = 'SELECT notification_id, message, status
            FROM notifications
            WHERE user_id = :user_id
            ORDER BY notification_id DESC';

    $statement = $this->database->prepare($sql);
    $statement->execute(['user_id' => $userId]);

    return $statement->fetchAll();
}

public function markNotificationAsRead(int $notificationId, int $userId): void
{
    $sql = 'UPDATE notifications
            SET status = "read"
            WHERE notification_id = :notification_id
              AND user_id = :user_id';

    $statement = $this->database->prepare($sql);
    $statement->execute([
        'notification_id' => $notificationId,
        'user_id' => $userId,
    ]);
}


public function getAllAnnouncements(): array
{
    $sql = 'SELECT announcement_id, title, message, date_created
            FROM announcements
            ORDER BY date_created DESC';

    $statement = $this->database->query($sql);

    return $statement->fetchAll();
}

public function getLatestAcademicCalendar(): ?array
{
    $sql = 'SELECT id, reopening_date, vacation_date
            FROM academic_calendar
            ORDER BY id DESC
            LIMIT 1';

    $statement = $this->database->query($sql);
    $calendar = $statement->fetch();

    return $calendar === false ? null : $calendar;
}

    public function register(array $studentData): int
    {
        $hashedPassword = password_hash(
            $studentData['password'],
            PASSWORD_DEFAULT
        );

        try {
            $this->database->beginTransaction();

            $userSql = 'INSERT INTO users (username, password, role)
                        VALUES (:username, :password, :role)';

            $userStatement = $this->database->prepare($userSql);
            $userStatement->execute([
                'username' => $studentData['username'],
                'password' => $hashedPassword,
                'role' => 'student',
            ]);

            $userId = (int) $this->database->lastInsertId();

            $studentSql = 'INSERT INTO students (
                                user_id,
                                full_name,
                                index_number,
                                program,
                                year_level,
                                academic_year,
                                email,
                                phone
                            ) VALUES (
                                :user_id,
                                :full_name,
                                :index_number,
                                :program,
                                :year_level,
                                :academic_year,
                                :email,
                                :phone
                            )';

            $studentStatement = $this->database->prepare($studentSql);
            $studentStatement->execute([
                'user_id' => $userId,
                'full_name' => $studentData['full_name'],
                'index_number' => $studentData['index_number'],
                'program' => $studentData['program'],
                'year_level' => $studentData['year_level'],
                'academic_year' => $studentData['academic_year'],
                'email' => $studentData['email'],
                'phone' => $studentData['phone'],
            ]);

            $this->database->commit();

            return $userId;
        } catch (PDOException $exception) {
            if ($this->database->inTransaction()) {
                $this->database->rollBack();
            }

            if ($exception->getCode() === '23000') {
                throw new RuntimeException(
                    'That username, email address, or index number is already in use.'
                );
            }

            error_log('Student registration failed: ' . $exception->getMessage());

            throw new RuntimeException(
                'We could not create your account. Please try again later.'
            );
        }
    }

    public function findByUserId(int $userId): ?array
    {
        $sql = 'SELECT
                    student_id,
                    user_id,
                    full_name,
                    index_number,
                    program,
                    year_level,
                    academic_year,
                    email,
                    phone
                FROM students
                WHERE user_id = :user_id
                LIMIT 1';

        $statement = $this->database->prepare($sql);
        $statement->execute(['user_id' => $userId]);

        $student = $statement->fetch();

        return $student === false ? null : $student;
    }

    public function getRecentNotifications(int $userId, int $limit = 5): array
    {
        $sql = 'SELECT notification_id, message, status
                FROM notifications
                WHERE user_id = :user_id
                ORDER BY notification_id DESC
                LIMIT :limit';

        $statement = $this->database->prepare($sql);
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getRecentAnnouncements(int $limit = 5): array
    {
        $sql = 'SELECT announcement_id, title, message, date_created
                FROM announcements
                ORDER BY date_created DESC
                LIMIT :limit';

        $statement = $this->database->prepare($sql);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}