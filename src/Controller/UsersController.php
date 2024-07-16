<?php

namespace App\Controller;

use App\Database\Database;
use App\Validator\UserValidator;
use App\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;

class UsersController
{
    public $database;
    public $validator;

    public function __construct(Database $database, UserValidator $validator)
    {
        $this->database = $database;
        $this->validator = $validator;
    }

    public function index(ServerRequest $request)
    {
        $data = $this->getRequestData($request);

        if (!$this->validator->validate($data)) {
            return JsonResponse::error(
                'Неверные входные данные',
                400,
                $this->validator->getErrors(),
                date('d.m.Y H:i:s'),
            );
        }

        $this->saveUserData(
            $data['name'],
            $data['surname'],
            $data['patronymic'],
            $data['email'],
            $data['phone']
        );

        return JsonResponse::success(
            'Данные пользователя сохранены',
            200,
            [],
            date('d.m.Y H:i:s'),
        );
    }

    private function getRequestData(ServerRequest $request): array
    {
        $contentType = $request->getHeaderLine('Content-Type');
        if ($contentType === 'application/json') {
            return json_decode($request->getBody()->getContents(), true);
        }

        return $request->getParsedBody();
    }

    private function saveUserData($name, $surname, $patronymic, $email, $phone)
    {
        $pdo = $this->database->getConnection();

        $query = "INSERT INTO users (name, surname, patronymic, email, phone) VALUES (:name, :surname, :patronymic, :email, :phone)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':patronymic', $patronymic);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
    }
}

