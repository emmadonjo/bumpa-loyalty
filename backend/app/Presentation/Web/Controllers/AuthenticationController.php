<?php

namespace App\Presentation\Web\Controllers;

use App\Domains\Accounts\Persistence\Entities\User;
use App\Domains\Accounts\Services\AuthService;
use App\Domains\Accounts\Services\UserService;
use app\Presentation\Web\Contracts\Controller;
use App\Presentation\Web\Requests\LoginRequest;
use App\Presentation\Web\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly AuthService $authService,
    ){}

    public function login(LoginRequest $request): JsonResponse
    {
        /**
         * I did not consider some security implementations such as rate limiting
         * and lockout for this test. In a production-scale application, these would've
         * been mandatorily in place.
         */
        $dto = $request->toDto();
        $user = $this->userService->findByEmail($dto->email);
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Incorrect email/password combination',
            ]);
        }

        if (!$this->authService->authenticate($dto)) {
            throw ValidationException::withMessages([
                'email' => 'Incorrect email/password combination',
            ]);
        }

        $token = $user->createToken('bumpa-loyalty-app')->accessToken;
        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'user' => UserResource::make($user),
            ]
        ]);
    }

    public function logout(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $this->authService->logout($user);

        return response()->json([
            'status' => true,
            'message' => 'Logout successful',
            'data' => [],
        ]);
    }
}
