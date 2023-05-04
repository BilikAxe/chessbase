<?php

function clearData(?string $val): string
{
    $val = trim($val);
    $val = stripslashes($val);
    $val = strip_tags($val);
    return htmlspecialchars($val);
}

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


function validate(array $data, PDO $connection): array
{
    $errorMessage = [];

    $emailError = validationEmail($data, $connection);
    if (!empty($emailError))
        $errorMessage['email'] = $emailError;

    $firstNameError = validationFirstName($data);
    if (!empty($firstNameError))
        $errorMessage['firstName'] = $firstNameError;

    $lastNameError = validationLastName($data);
    if (!empty($lastNameError))
        $errorMessage['lastName'] = $lastNameError;

    $surnameError = validationSurname($data);
    if (!empty($surnameError))
        $errorMessage['surname'] = $surnameError;

    $phoneNumberError = validationPhoneNumber($data);
    if (!empty($phoneNumberError))
        $errorMessage['phoneNumber'] = $phoneNumberError;

    $passwordError = validationPassword($data);
    if (!empty($passwordError))
        $errorMessage['password'] = $passwordError;

    return $errorMessage;
}


function validationEmail(?array $data, PDO $connection): string|null
{
    $emailError = $data['email'];
    if (empty($emailError))
        return 'Invalid Email';

    if (strlen($emailError) < 6)
        return 'Email is too short';

    $result = $connection->prepare("SELECT email FROM users WHERE email = ?");
    $result->execute([$emailError]);
    $exists = $result->fetch();

    if ($exists) {
        return "This email already exists";
    }

    return null;
}


function validationFirstName(?array $data): string|null
{
    $firstNameError = $data['firstName'];
    if (empty($firstNameError))
        return 'Invalid First Name';

    if (strlen($firstNameError) < 2)
        return 'First Name is too short';

    return null;
}


function validationLastName(?array $data): string|null
{
    $lastNameError = $data['lastName'];
    if (empty($lastNameError))
        return 'Invalid Last Name';

    if (strlen($lastNameError) < 2)
        return 'Last Name is too short';

    return null;
}


function validationSurname(?array $data): string|null
{
    $surnameError = $data['surname'];
    if (empty($surnameError))
        return 'Invalid Surname';

    if (strlen($surnameError) < 5)
        return 'Surname is too short';

    return null;
}


function validationPhoneNumber(?array $data): string|null
{
    $phoneNumberError = $data['phoneNumber'];
    if (empty($phoneNumberError))
        return 'Invalid Phone Number';

    if (strlen($phoneNumberError) < 10)
        return 'Phone Number is too short';

    return null;
}


function validationPassword(?array $data): string|null
{
    $passError = $data['password'];
    if (empty($passError))
        return 'Invalid Password';

    if (strlen($passError) < 4)
        return 'Password is too short';

    return null;
}




