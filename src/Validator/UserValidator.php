<?php

namespace App\Validator;

use App\Traits\ValidatorTrait;

class UserValidator
{
    use ValidatorTrait;

    public function validate(array $data): bool
    {
        $this->validateName($data);
        $this->validateSurname($data);
        $this->validatePatronymic($data);
        $this->validateEmail($data);
        $this->validatePhone($data);

        if (array_key_exists('email', $data)) {
            $this->checkEmailUniqueness($data['email']);
        }

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function validateName(array $data): void
    {
        $this->validateRequired($data, 'name');

        $this->validatePattern($data, 'name', '/^[a-zA-Zа-яА-Я]+$/u', 'Имя должно содержать только буквы');
    }

    private function validateSurname(array $data): void
    {
        $this->validateRequired($data, 'surname');

        $this->validatePattern($data, 'surname', '/^[a-zA-Zа-яА-Я]+$/u', 'Фамилия должна содержать только буквы');
    }

    private function validatePatronymic(array $data): void
    {
        $this->validateRequired($data, 'patronymic');

        $this->validatePattern($data, 'patronymic', '/^[a-zA-Zа-яА-Я]+$/u', 'Отчество должно содержать только буквы');
    }

    private function validateEmail(array $data): void
    {
        $this->validateRequired($data, 'email');

        if (array_key_exists('email', $data) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Некорректный email-адрес';
        }
    }

    private function validatePhone(array $data): void
    {
        $this->validateRequired($data, 'phone');

        $this->validatePattern($data, 'phone', '/^[0-9+]+$/', 'Номер телефона должен содержать только цифры');
    }

    private function checkEmailUniqueness($email)
    {
        global $pdo;

        $query = "SELECT COUNT(*) as count FROM users WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result['count'] > 0) {
            $this->errors['email'] = 'Пользователь с таким email-адресом уже существует';
        }
    }
}