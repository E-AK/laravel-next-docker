<?php

namespace App\Actions;

use App\Http\Requests\Api\Auth\AuthRequest;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthAction
{
    /**
     * @param AuthRequest $request
     * @param User $user
     * @return JsonResource
     */
    public function getToken(AuthRequest $request, User $user): JsonResource
    {
        $token = $user->createToken($request->login)->plainTextToken;

        $user->api_token = $token;
        $user->save();

        return new JsonResource([
            'token' => $token,
        ]);
    }
}
