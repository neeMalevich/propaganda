<?php

namespace App\Traits;

trait ValidatorTrait
{
    public $errors = [];

    public function validateRequired(array $data, string $field): void
    {
        if (!array_key_exists($field, $data) || empty($data[$field])) {
            $this->errors[$field] = "Поле является обязательным";
        }
    }

    public function validatePattern(array $data, string $field, string $pattern, string $errorMessage): void
    {
        if (array_key_exists($field, $data) && !preg_match($pattern, $data[$field])) {
            $this->errors[$field] = $errorMessage;
        }
    }
}