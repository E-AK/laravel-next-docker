<?php

namespace App\ApiResource;

use Symfony\Component\HttpFoundation\JsonResponse;

class UserNotFoundResource extends JsonResponse
{
    public function __construct(
        mixed $data = [
            'message' => 'Неверные логин или пароль',
            'errors' => [
                'common' => 'Неверные логин или пароль',
            ]
        ],
        int $status = 401,
        array $headers = [],
        bool $json = false
    )
    {
        parent::__construct($data, $status, $headers, $json);
    }
}