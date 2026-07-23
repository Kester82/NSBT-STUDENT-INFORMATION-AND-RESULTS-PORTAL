<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/config/database.php';

class User
{
    private PDO $database;

    public function __construct()
    {
        $this->database = Database::connect();
    }

    public function findByUsername(string $username): ?array
    {
        $sql = 'SELECT user_id, username, password, role
                FROM users
                WHERE username = :username
                LIMIT 1';

        $statement = $this->database->prepare($sql);
        $statement->execute([
            'username' => $username,
        ]);

        $user = $statement->fetch();

        return $user === false ? null : $user;
    }

    public function findByEmail(string $email): ?array
    {
        $sql = 'SELECT DISTINCT u.user_id, u.username, u.password, u.role
                FROM users u
                LEFT JOIN students s ON s.user_id = u.user_id
                LEFT JOIN lecturers l ON l.user_id = u.user_id
                WHERE LOWER(s.email) = :student_email
                   OR LOWER(l.email) = :lecturer_email
                LIMIT 1';

        $statement = $this->database->prepare($sql);
        $statement->execute([
            'student_email' => strtolower(trim($email)),
            'lecturer_email' => strtolower(trim($email)),
        ]);

        $user = $statement->fetch();

        return $user === false ? null : $user;
    }

    public function createPasswordResetToken(int $userId): string
    {
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);

        try {
            $this->database->beginTransaction();

            $deleteStatement = $this->database->prepare(
                'DELETE FROM password_reset_tokens
                 WHERE user_id = :user_id
                    OR expires_at < NOW()
                    OR used_at IS NOT NULL'
            );

            $deleteStatement->execute([
                'user_id' => $userId,
            ]);

            $insertStatement = $this->database->prepare(
                'INSERT INTO password_reset_tokens (user_id, token_hash, expires_at)
                 VALUES (:user_id, :token_hash, DATE_ADD(NOW(), INTERVAL 1 HOUR))'
            );

            $insertStatement->execute([
                'user_id' => $userId,
                'token_hash' => $tokenHash,
            ]);

            $this->database->commit();

            return $token;
        } catch (Throwable $exception) {
            if ($this->database->inTransaction()) {
                $this->database->rollBack();
            }

            throw $exception;
        }
    }

    public function isPasswordResetTokenValid(string $token): bool
    {
        $tokenHash = hash('sha256', $token);

        $statement = $this->database->prepare(
            'SELECT reset_id
             FROM password_reset_tokens
             WHERE token_hash = :token_hash
               AND used_at IS NULL
               AND expires_at > NOW()
             LIMIT 1'
        );

        $statement->execute([
            'token_hash' => $tokenHash,
        ]);

        return $statement->fetch() !== false;
    }

    public function resetPasswordWithToken(string $token, string $plainPassword): bool
    {
        $tokenHash = hash('sha256', $token);

        try {
            $this->database->beginTransaction();

            $tokenStatement = $this->database->prepare(
                'SELECT reset_id, user_id
                 FROM password_reset_tokens
                 WHERE token_hash = :token_hash
                   AND used_at IS NULL
                   AND expires_at > NOW()
                 LIMIT 1
                 FOR UPDATE'
            );

            $tokenStatement->execute([
                'token_hash' => $tokenHash,
            ]);

            $resetRequest = $tokenStatement->fetch();

            if ($resetRequest === false) {
                $this->database->rollBack();
                return false;
            }

            $passwordStatement = $this->database->prepare(
                'UPDATE users
                 SET password = :password
                 WHERE user_id = :user_id'
            );

            $passwordStatement->execute([
                'password' => password_hash($plainPassword, PASSWORD_DEFAULT),
                'user_id' => $resetRequest['user_id'],
            ]);

            $usedStatement = $this->database->prepare(
                'UPDATE password_reset_tokens
                 SET used_at = NOW()
                 WHERE reset_id = :reset_id'
            );

            $usedStatement->execute([
                'reset_id' => $resetRequest['reset_id'],
            ]);

            $this->database->commit();

            return true;
        } catch (Throwable $exception) {
            if ($this->database->inTransaction()) {
                $this->database->rollBack();
            }

            throw $exception;
        }
    }

    public function create(string $username, string $plainPassword, string $role = 'student'): int
    {
        $allowedRoles = ['student', 'lecturer', 'admin'];

        if (!in_array($role, $allowedRoles, true)) {
            throw new InvalidArgumentException('Invalid user role.');
        }

        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO users (username, password, role)
                VALUES (:username, :password, :role)';

        $statement = $this->database->prepare($sql);
        $statement->execute([
            'username' => $username,
            'password' => $hashedPassword,
            'role' => $role,
        ]);

        return (int) $this->database->lastInsertId();
    }
}