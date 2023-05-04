<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');

    $errorMessages = validate($_POST, $connection);

    if (empty($errorMessages)) {

        $userData = $connection->query("SELECT * FROM users");

        $_SESSION['id'] = $userData['id'];

        header("Location: /main");
        die;
    }
}

function clearData(string $val): string
{
    $val = trim($val);
    $val = stripslashes($val);
    $val = strip_tags($val);
    return htmlspecialchars($val);
}

function validate(array $data, PDO $connection): array
{
    $errorMessages = [];

    $loginError = validateLogin($data, $connection);
    if (!empty($loginError)) {
        $errorMessages['email'] = $loginError;
    }

    $passwordError = validatePassword($data, $connection);
    if (!empty($passwordError)) {
        $errorMessages['password'] = $passwordError;
    }

    return $errorMessages;
}


function validateLogin(array $data, PDO $connection): string|null
{
    $email = $data['email'] ?? null;

    if (empty($login)) {
        return 'Invalid Login';

    } else {
        $result = $connection->prepare("SELECT * FROM users WHERE email = ?");
        $result->execute([$email]);
        $userData = $result->fetch();

        if (!$userData) {
            return 'Wrong Login';
        }
    }

    return null;
}


function validatePassword(array $data, PDO $connection): string|null
{
    $password = $data['password'] ?? null;

    if (empty($password)) {
        return 'Invalid Password';

    } else {
        $result = $connection->prepare("SELECT * FROM users WHERE email = ?");
        $result->execute([$data['email']]);
        $userData = $result->fetch(PDO::FETCH_ASSOC);

        if (!password_verify($password, $userData['password'])) {
            return 'Wrong Password';
        }
    }

    return null;
}


require_once './forms/signin.phtml';