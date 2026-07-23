<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('This script can only run from the command line.');
}

require_once dirname(__DIR__) . '/config/database.php';

function prompt(string $label): string
{
    echo $label;
    $value = fgets(STDIN);

    return $value === false ? '' : trim($value);
}

$username = prompt('Admin username: ');
$password = prompt('Admin password (minimum 8 characters): ');
$passwordConfirmation = prompt('Confirm password: ');

if ($username === '') {
    exit("Error: Username is required.\n");
}

if (strlen($password) < 8) {
    exit("Error: Password must contain at least 8 characters.\n");
}

if (!hash_equals($password, $passwordConfirmation)) {
    exit("Error: Password confirmation does not match.\n");
}

try {
    $database = Database::connect();

    $statement = $database->prepare(
        'INSERT INTO users (username, password, role)
         VALUES (:username, :password, :role)'
    );

    $statement->execute([
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => 'admin',
    ]);

    echo "Admin account created successfully.\n";
} catch (PDOException $exception) {
    if ($exception->getCode() === '23000') {
        exit("Error: That username is already in use.\n");
    }

    error_log('Admin account creation failed: ' . $exception->getMessage());

    exit("Error: Unable to create the admin account.\n");
}