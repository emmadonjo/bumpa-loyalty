<?php

namespace App\Domains\Accounts\Services;

use App\Domains\Accounts\Persistence\Entities\User;
use App\Presentation\Web\DataTransferObjects\LoginDto;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function authenticate(LoginDto $dto): bool
    {
        return Auth::attempt(['email' => $dto->email, 'password' => $dto->password]);
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
        session()->flush();
    }
}
