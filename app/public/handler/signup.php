<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');

    $errorMessage = validate($_POST, $connection);

    if (empty($errorMessage)) {
        $email = clearData($_POST['email'] ?? null);
        $firstName = clearData($_POST['firstName'] ?? null);
        $lastName = clearData($_POST['lastName'] ?? null);
        $surname = clearData($_POST['surname'] ?? null);
        $phoneNumber = clearData($_POST['phoneNumber'] ?? null);
        $password = $_POST['password'] ?? null;

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
    }
}


function clearData(?string $val): string
{
    $val = trim($val);
    $val = stripslashes($val);
    $val = strip_tags($val);
    return htmlspecialchars($val);
}


function validate(array $data, PDO $connection): array
{
    $errorMessages = [];

    $emailError = validationEmail($data, $connection);
    if (!empty($emailError)) {
        $errorMessages['email'] = $emailError;
    }

    $firstNameError = validationFirstName($data);
    if (!empty($firstNameError)) {
        $errorMessages['firstName'] = $firstNameError;
    }

    $lastNameError = validationLastName($data);
    if (!empty($lastNameError)) {
        $errorMessages['lastName'] = $lastNameError;
    }

    $surnameError = validationSurname($data);
    if (!empty($surnameError)) {
        $errorMessages['surname'] = $surnameError;
    }

    $phoneNumberError = validationPhoneNumber($data);
    if (!empty($phoneNumberError)) {
        $errorMessages['phoneNumber'] = $phoneNumberError;
    }

    $passwordError = validationPassword($data);
    if (!empty($passwordError)) {
        $errorMessages['password'] = $passwordError;
    }

    return $errorMessages;
}


function validationEmail(?array $data, PDO $connection): string|null
{
    $email = $data['email'];
    if (empty($email)) {
        return 'Invalid Email';
    }

    if (strlen($email) < 6) {
        return 'Email is too short';
    }

    $result = $connection->prepare("SELECT email FROM users WHERE email = ?");
    $result->execute([$email]);
    $exists = $result->fetch();

    if ($exists) {
        return "This email already exists";
    }

    return null;
}


function validationFirstName(?array $data): string|null
{
    $firstName = $data['firstName'];
    if (empty($firstName)) {
        return 'Invalid First Name';
    }

    if (strlen($firstName) < 2) {
        return 'First Name is too short';
    }

    return null;
}


function validationLastName(?array $data): string|null
{
    $lastName = $data['lastName'];
    if (empty($lastName)) {
        return 'Invalid Last Name';
    }

    if (strlen($lastName) < 2) {
        return 'Last Name is too short';
    }

    return null;
}


function validationSurname(?array $data): string|null
{
    $surname = $data['surname'];
    if (empty($surname)) {
        return 'Invalid Surname';
    }

    if (strlen($surname) < 5) {
        return 'Surname is too short';
    }

    return null;
}


function validationPhoneNumber(?array $data): string|null
{
    $phoneNumber = $data['phoneNumber'];
    if (empty($phoneNumber)) {
        return 'Invalid Phone Number';
    }

    if (strlen($phoneNumber) < 10) {
        return 'Phone Number is too short';
    }

    return null;
}


function validationPassword(?array $data): string|null
{
    $pass = $data['password'];
    if (empty($pass)) {
        return 'Invalid Password';
    }

    if (strlen($pass) < 4) {
        return 'Password is too short';
    }

    return null;
}



require_once './forms/signup.phtml';
