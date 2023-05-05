<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');

    $errorMessages = validate($_POST);

    if (empty($errorMessages)) {

        $email = $_POST['email'];
        $password = $_POST['password'];
        $errorMessages = '';

        $result = $connection->prepare("SELECT * FROM users WHERE email = ?");
        $result->execute([$email]);
        $userData = $result->fetch();

        if ($userData) {
            if (password_verify($password, $userData['password'])) {

                $_SESSION['id'] = $userData['id'];

                header("Location: /main");
                die;
            }

            $errorMessages = 'Invalid Login or Password';
        }

        $errorMessages = 'Invalid Login or Password';
    }
}


function clearData(string $val): string
{
    $val = trim($val);
    $val = stripslashes($val);
    $val = strip_tags($val);
    return htmlspecialchars($val);
}

function validate(array $data): string
{
    $errorMessages = '';

    $loginError = validateLogin($data);
    if (!empty($loginError)) {
        $errorMessages = $loginError;
    }

    $passwordError = validatePassword($data);
    if (!empty($passwordError)) {
        $errorMessages = $passwordError;
    }

    return $errorMessages;
}


function validateLogin(array $data): string|null
{
    $email = $data['email'] ?? null;

    if (empty($email)) {
        return 'Invalid Login or Password';
    }

    return null;
}


function validatePassword(array $data): string|null
{
    $password = $data['password'] ?? null;

    if (empty($password)) {
        return 'Invalid Login or Password';
    }

    return null;
}


require_once './forms/signin.phtml';