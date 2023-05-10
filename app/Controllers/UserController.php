<?php

namespace banana\Controllers;

use banana\PdoInterface;
use PDO;

class UserController implements PdoInterface
{
    private PDO $connection;


    public function setConnection(PDO $connection): void
    {
        $this->connection = $connection;
    }


    public function signUp(): array
    {
        $errorMessages = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $connection = $this->connection;

            $errorMessages = $this->validate($_POST, $connection);

            if (empty($errorMessages)) {
                $email = $this->clearData($_POST['email']);
                $firstName = $this->clearData($_POST['firstName']);
                $lastName = $this->clearData($_POST['lastName']);
                $surname = $this->clearData($_POST['surname']);
                $phoneNumber = $this->clearData($_POST['phoneNumber']);
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

        return [
            './views/signup.phtml',
            ['errorMessages' => $errorMessages],
            false
        ];
    }


    private function clearData(string $val): string
    {
        $val = trim($val);
        $val = stripslashes($val);
        $val = strip_tags($val);
        return htmlspecialchars($val);
    }


    private function validate(array $data, PDO $connection): array
    {
        $errorMessages = [];

        $emailError = $this->validateEmail($data, $connection);
        if (!empty($emailError)) {
            $errorMessages['email'] = $emailError;
        }

        $firstNameError = $this->validateFirstName($data);
        if (!empty($firstNameError)) {
            $errorMessages['firstName'] = $firstNameError;
        }

        $lastNameError = $this->validateLastName($data);
        if (!empty($lastNameError)) {
            $errorMessages['lastName'] = $lastNameError;
        }

        $surnameError = $this->validateSurname($data);
        if (!empty($surnameError)) {
            $errorMessages['surname'] = $surnameError;
        }

        $phoneNumberError = $this->validatePhoneNumber($data);
        if (!empty($phoneNumberError)) {
            $errorMessages['phoneNumber'] = $phoneNumberError;
        }

        $passwordError = $this->validatePassword($data);
        if (!empty($passwordError)) {
            $errorMessages['password'] = $passwordError;
        }

        return $errorMessages;
    }


    private function validateEmail(array $data, PDO $connection): string|null
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


    private function validateFirstName(array $data): string|null
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


    private function validateLastName(array $data): string|null
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


    private function validateSurname(array $data): string|null
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


    private function validatePhoneNumber(array $data): string|null
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


    private function validatePassword(array $data): string|null
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


    public function signIn(): array
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        $errorMessages = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $connection = $this->connection;

            $errorMessages = $this->validateLoginAndPass($_POST);

            if (empty($errorMessages)) {

                $email = $this->clearData($_POST['email']);
                $password = $_POST['password'];

                $result = $connection->prepare("SELECT * FROM users WHERE email = ?");
                $result->execute([$email]);
                $userData = $result->fetch();

                if ($userData && password_verify($password, $userData['password'])) {

                    $_SESSION['id'] = $userData['id'];

                    header("Location: /main");
                    die;
                }

                $errorMessages = 'Invalid Login or Password';
            }
        }

        return [
            './views/signin.phtml',
            ['errorMessages' => $errorMessages],
            false
        ];
    }


    private function validateLoginAndPass(array $data): string
    {
        $errorMessages = '';

        $loginError = $this->validateLogin($data);
        if (!empty($loginError)) {
            $errorMessages = $loginError;
        }

        $passwordError = $this->validatePass($data);
        if (!empty($passwordError)) {
            $errorMessages = $passwordError;
        }

        return $errorMessages;
    }


    private function validateLogin(array $data): string|null
    {
        $email = $data['email'] ?? null;

        if (empty($email)) {
            return 'Invalid Login or Password';
        }

        return null;
    }


    private function validatePass(array $data): string|null
    {
        $password = $data['password'] ?? null;

        if (empty($password)) {
            return 'Invalid Login or Password';
        }

        return null;
    }

}