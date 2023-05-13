<?php

namespace banana\Controllers;

use banana\Entity\User;
use banana\Repository\UserRepository;

class UserController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function signUp(): array
    {
        $errorMessages = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $errorMessages = $this->validate($_POST);

            if (empty($errorMessages)) {
                $email = $this->clearData($_POST['email']);
                $firstName = $this->clearData($_POST['firstName']);
                $lastName = $this->clearData($_POST['lastName']);
                $surname = $this->clearData($_POST['surname']);
                $phoneNumber = $this->clearData($_POST['phoneNumber']);
                $password = $_POST['password'];

                $password = password_hash($password, PASSWORD_DEFAULT);

                $user = new User($email, $firstName, $lastName, $surname, $phoneNumber, $password);

                $this->userRepository->save($user);

                header("Location: /signin");
                die;
            }
        }

        return [
            '../Views/signup.phtml',
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


    private function validate(array $data): array
    {
        $errorMessages = [];

        $emailError = $this->validateEmail($data);
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


    private function validateEmail(array $data): string|null
    {
        $email = $data['email'] ?? null;
        if (empty($email)) {
            return 'Invalid Email';
        } else {
            $exists = $this->userRepository->getEmail($email);

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

            $errorMessages = $this->validateLoginAndPass($_POST);

            if (empty($errorMessages)) {

                $email = $_POST['email'];
                $password = $_POST['password'];

                $userData = $this->userRepository->getEmail($email);

                if ($userData && password_verify($password, $userData->getPassword())) {

                    $_SESSION['id'] = $userData->getId();

                    header("Location: /main");
                    die;
                }

                $errorMessages = 'Invalid Login or Password';
            }
        }

        return [
            '../Views/signin.phtml',
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