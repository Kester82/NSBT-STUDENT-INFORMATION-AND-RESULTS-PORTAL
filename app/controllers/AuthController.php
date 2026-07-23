<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/app/models/User.php';
require_once dirname(__DIR__, 2) . '/app/models/Student.php';
require_once dirname(__DIR__, 2) . '/app/services/MailService.php';

class AuthController
{
    private User $userModel;
    private Student $studentModel;
    private MailService $mailService;

    public function __construct()
    {
        $this->userModel = new User();
        $this->studentModel = new Student();
        $this->mailService = new MailService();
    }

    public function login(string $username, string $password): array
    {
        $this->startSession();

        $username = trim($username);

        if ($username === '' || $password === '') {
            return [
                'success' => false,
                'message' => 'Enter both your username and password.',
            ];
        }

        $user = $this->userModel->findByUsername($username);

        if ($user === null || !password_verify($password, $user['password'])) {
            return [
                'success' => false,
                'message' => 'Invalid username or password.',
            ];
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = (int) $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['logged_in'] = true;

        return [
            'success' => true,
            'role' => $user['role'],
        ];
    }

    public function registerStudent(array $data): array
    {
        $fullName = trim((string) ($data['full_name'] ?? ''));
        $username = trim((string) ($data['username'] ?? ''));
        $indexNumber = trim((string) ($data['index_number'] ?? ''));
        $program = trim((string) ($data['program'] ?? ''));
        $academicYear = trim((string) ($data['academic_year'] ?? ''));
        $email = strtolower(trim((string) ($data['email'] ?? '')));
        $phone = trim((string) ($data['phone'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $confirmPassword = (string) ($data['confirm_password'] ?? '');

        $yearLevel = filter_var(
            $data['year_level'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1, 'max_range' => 8]]
        );

        $errors = [];

        if (mb_strlen($fullName) < 2 || mb_strlen($fullName) > 150) {
            $errors['full_name'] = 'Enter your full name.';
        }

        if (!preg_match('/^[A-Za-z0-9._-]{3,50}$/', $username)) {
            $errors['username'] = 'Username must be 3-50 characters and use only letters, numbers, dots, hyphens, or underscores.';
        }

        if (!preg_match('/^\d{1,30}$/', $indexNumber)) {
            $errors['index_number'] = 'Enter your numeric school index number.';
        }

        if (mb_strlen($program) < 2 || mb_strlen($program) > 150) {
            $errors['program'] = 'Enter your programme of study.';
        }

        if ($yearLevel === false) {
            $errors['year_level'] = 'Choose a valid year level.';
        }

        if (!preg_match('/^\d{4}\/\d{4}$/', $academicYear)) {
            $errors['academic_year'] = 'Use the format YYYY/YYYY, for example 2025/2026.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
            $errors['email'] = 'Enter a valid email address.';
        }

        if ($phone !== '' && !preg_match('/^[0-9+\-\s()]{7,30}$/', $phone)) {
            $errors['phone'] = 'Enter a valid phone number or leave it blank.';
        }

        if (strlen($password) < 8) {
            $errors['password'] = 'Your password must contain at least 8 characters.';
        }

        if ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'The passwords do not match.';
        }

        if ($errors !== []) {
            return [
                'success' => false,
                'errors' => $errors,
            ];
        }

        try {
            $this->studentModel->register([
                'full_name' => $fullName,
                'username' => $username,
                'index_number' => $indexNumber,
                'program' => $program,
                'year_level' => $yearLevel,
                'academic_year' => $academicYear,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
            ]);

            return ['success' => true];
        } catch (RuntimeException $exception) {
            return [
                'success' => false,
                'errors' => [
                    'general' => $exception->getMessage(),
                ],
            ];
        }
    }

    public function requestPasswordReset(string $email): array
    {
        $email = strtolower(trim($email));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Enter a valid email address.',
            ];
        }

        $user = $this->userModel->findByEmail($email);

        if ($user !== null) {
            try {
                $token = $this->userModel->createPasswordResetToken(
                    (int) $user['user_id']
                );

                $resetUrl = APP_BASE_URL
                    . '/public/reset-password.php?token='
                    . urlencode($token);

                $emailWasSent = $this->mailService->sendPasswordResetEmail(
                    $email,
                    $resetUrl
                );

                if (!$emailWasSent) {
                    error_log('NSBT Portal password-reset email could not be sent.');
                }
            } catch (Throwable $exception) {
                error_log('NSBT Portal password-reset request failed.');
            }
        }

        /*
         * This message remains the same whether an account exists or not.
         * That prevents people from checking which email addresses are registered.
         */
        return [
            'success' => true,
            'message' => 'If an account with that email address exists, a password reset link has been sent.',
        ];
    }

    public function validatePasswordResetToken(string $token): bool
    {
        return preg_match('/^[a-f0-9]{64}$/', $token) === 1
            && $this->userModel->isPasswordResetTokenValid($token);
    }

    public function resetPassword(
        string $token,
        string $password,
        string $confirmPassword
    ): array {
        if (!$this->validatePasswordResetToken($token)) {
            return [
                'success' => false,
                'message' => 'This password reset link is invalid, expired, or has already been used.',
            ];
        }

        if (strlen($password) < 8) {
            return [
                'success' => false,
                'message' => 'Your password must contain at least 8 characters.',
            ];
        }

        if ($password !== $confirmPassword) {
            return [
                'success' => false,
                'message' => 'The passwords do not match.',
            ];
        }

        try {
            $wasReset = $this->userModel->resetPasswordWithToken(
                $token,
                $password
            );

            if (!$wasReset) {
                return [
                    'success' => false,
                    'message' => 'This password reset link is invalid, expired, or has already been used.',
                ];
            }

            return [
                'success' => true,
                'message' => 'Your password has been reset. You can now log in.',
            ];
        } catch (Throwable $exception) {
            error_log('NSBT Portal password reset failed.');

            return [
                'success' => false,
                'message' => 'Unable to reset your password right now. Please try again.',
            ];
        }
    }

    public static function csrfToken(): string
    {
        self::startSession();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function csrfTokenIsValid(?string $submittedToken): bool
    {
        self::startSession();

        return isset($_SESSION['csrf_token'])
            && is_string($submittedToken)
            && hash_equals($_SESSION['csrf_token'], $submittedToken);
    }

    public static function isLoggedIn(): bool
    {
        self::startSession();

        return !empty($_SESSION['logged_in'])
            && isset($_SESSION['user_id'], $_SESSION['role']);
    }

    public static function requireRole(array $allowedRoles): void
    {
        self::startSession();

        if (!self::isLoggedIn() || !in_array($_SESSION['role'], $allowedRoles, true)) {
            header('Location: ' . APP_URL . '/public/login.php');
            exit;
        }
    }

    public static function redirectForRole(string $role): string
    {
        $routes = [
            'student' => APP_URL . '/public/student/dashboard.php',
            'lecturer' => APP_URL . '/public/lecturer/dashboard.php',
            'admin' => APP_URL . '/public/admin/dashboard.php',
        ];

        return $routes[$role] ?? APP_URL . '/public/login.php';
    }

        public static function logout(): void
    {
        self::startSession();

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                || (int) ($_SERVER['SERVER_PORT'] ?? 80) === 443;

            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => APP_URL . '/',
                'secure' => $isHttps,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        }

                session_destroy();
    }

    private static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                || (int) ($_SERVER['SERVER_PORT'] ?? 80) === 443;

            ini_set('session.use_strict_mode', '1');
            ini_set('session.use_only_cookies', '1');

            session_name('nsbt_portal_session');

            session_set_cookie_params([
                'lifetime' => 0,
                'path' => APP_URL . '/',
                'secure' => $isHttps,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);

            session_start();
        }
    }
}