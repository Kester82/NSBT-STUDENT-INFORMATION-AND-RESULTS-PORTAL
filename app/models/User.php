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