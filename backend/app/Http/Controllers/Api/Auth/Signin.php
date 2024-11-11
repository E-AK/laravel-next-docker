<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\AuthAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\AuthRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Resources\Json\JsonResource;

class Signin extends Controller
{
    public function __construct(
        private readonly AuthAction $action
    ) {

    }

    /**
     * Handle the incoming request.
     * @throws ValidationException
     */
    public function __invoke(AuthRequest $request)
    {
        $request->validated();

        $user = User::query()
            ->where('login', $request->login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'fail' => 'Неверный логин или пароль',
            ]);
        }

        return $this->action->getToken($request, $user);
    }
}
