<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('This script can only run from the terminal.');
}

require_once __DIR__ . '/../app/models/Lecturer.php';

function prompt(string $label): string
{
    echo $label;
    $value = fgets(STDIN);

    return $value === false ? '' : trim($value);
}

$fullName = prompt('Lecturer full name: ');
$email = prompt('Lecturer email: ');
$username = prompt('Lecturer username: ');
$password = prompt('Temporary password (minimum 8 characters): ');

if (
    strlen($fullName) < 2
    || !filter_var($email, FILTER_VALIDATE_EMAIL)
    || !preg_match('/^[A-Za-z0-9._-]{3,50}$/', $username)
    || strlen($password) < 8
) {
    exit("Invalid details. No account was created.\n");
}

try {
    $lecturer = new Lecturer();

    $lecturer->createAccount([
        'full_name' => $fullName,
        'email' => $email,
        'username' => $username,
        'password' => $password,
    ]);

    echo "Lecturer account created successfully.\n";
} catch (RuntimeException $exception) {
    exit($exception->getMessage() . "\n");
}