<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');

    $errorMessages = validate($_POST, $connection);

    if (empty($errorMessages)) {
        $email = clearData($_POST['email']);
        $firstName = clearData($_POST['firstName']);
        $lastName = clearData($_POST['lastName']);
        $surname = clearData($_POST['surname']);
        $phoneNumber = clearData($_POST['phoneNumber']);
        $password = $_POST['password'];

        $password = password_hash($password, PASSWORD_DEFAULT);

        $sth = $connection->prepare("
                INSERT INTO users (email, first_name, last_name, surname, phone_number, password) 
                VALUES (:email, :first_name, :last_name, :surname, :phone_number, :password)
                ");

        $sth->execute([
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'surname' => $surname,
            'phone_number' => $phoneNumber,
            'password' => $password
        ]);

        header("Location: /signin");
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

    $emailError = validateEmail($data, $connection);
    if (!empty($emailError)) {
        $errorMessages['email'] = $emailError;
    }

    $firstNameError = validateFirstName($data);
    if (!empty($firstNameError)) {
        $errorMessages['firstName'] = $firstNameError;
    }

    $lastNameError = validateLastName($data);
    if (!empty($lastNameError)) {
        $errorMessages['lastName'] = $lastNameError;
    }

    $surnameError = validateSurname($data);
    if (!empty($surnameError)) {
        $errorMessages['surname'] = $surnameError;
    }

    $phoneNumberError = validatePhoneNumber($data);
    if (!empty($phoneNumberError)) {
        $errorMessages['phoneNumber'] = $phoneNumberError;
    }

    $passwordError = validatePassword($data);
    if (!empty($passwordError)) {
        $errorMessages['password'] = $passwordError;
    }

    return $errorMessages;
}


function validateEmail(array $data, PDO $connection): string|null
{
    $email = $data['email'] ?? null;
    if (empty($email)) {
        return 'Invalid Email';
    } else {
        $result = $connection->prepare("SELECT email FROM users WHERE email = ?");
        $result->execute([$email]);
        $exists = $result->fetch();

        if ($exists) {
            return "This email already exists";
        }
    }

    if (strlen($email) < 6) {
        return 'Email is too short';
    }

    return null;
}


function validateFirstName(array $data): string|null
{
    $firstName = $data['firstName'] ?? null;
    if (empty($firstName)) {
        return 'Invalid First Name';
    }

    if (strlen($firstName) < 2) {
        return 'First Name is too short';
    }

    return null;
}


function validateLastName(array $data): string|null
{
    $lastName = $data['lastName'] ?? null;
    if (empty($lastName)) {
        return 'Invalid Last Name';
    }

    if (strlen($lastName) < 2) {
        return 'Last Name is too short';
    }

    return null;
}


function validateSurname(array $data): string|null
{
    $surname = $data['surname'] ?? null;
    if (empty($surname)) {
        return 'Invalid Surname';
    }

    if (strlen($surname) < 5) {
        return 'Surname is too short';
    }

    return null;
}


function validatePhoneNumber(array $data): string|null
{
    $phoneNumber = $data['phoneNumber'] ?? null;
    if (empty($phoneNumber)) {
        return 'Invalid Phone Number';
    }

    if (strlen($phoneNumber) < 10) {
        return 'Phone Number is too short';
    }

    return null;
}


function validatePassword(array $data): string|null
{
    $pass = $data['password'] ?? null;
    if (empty($pass)) {
        return 'Invalid Password';
    }

    if (strlen($pass) < 4) {
        return 'Password is too short';
    }

    return null;
}


return [
    './views/signup.phtml',
    ['errorMessages'],
    false
];
