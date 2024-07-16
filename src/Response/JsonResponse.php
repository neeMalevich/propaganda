<?php

namespace App\Response;

class JsonResponse
{
    public static function success(string $message, int $code, array $data, string $time): array
    {
        return [
            'message' => $message,
            'code' => $code,
            'time' => $time,
            'data' => $data,
        ];
    }

    public static function error(string $message, int $code, array $errors, string $time): array
    {
        return [
            'message' => $message,
            'code' => $code,
            'time' => $time,
            'errors' => $errors,
        ];
    }
}