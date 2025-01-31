<?php

namespace App\ApiResource;

use Symfony\Component\HttpFoundation\JsonResponse;

class TokenResource extends JsonResponse
{
    public function __construct(
        string $token,
        int $status = 200,
        array $headers = [],
        bool $json = false
    )
    {
        parent::__construct(
            [
                'data' => ['token' => $token]
            ],
            $status,
            $headers,
            $json
        );
    }
}