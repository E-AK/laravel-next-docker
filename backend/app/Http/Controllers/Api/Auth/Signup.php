<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\AuthAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\AuthRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class Signup extends Controller
{
    public function __construct(
        private readonly AuthAction $action
    ) {

    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(AuthRequest $request)
    {
        $request->validated();

        if ($request->password !== $request->repeat_password) {
            throw ValidationException::withMessages([
                'fail' => 'Пароли не совпадают',
            ]);
        }

        $user = User::create([
            'login' => $request->login,
            'password' => bcrypt($request->password)
        ]);

        return $this->action->getToken($request, $user);
    }
}
