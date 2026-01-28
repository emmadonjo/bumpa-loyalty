<?php

namespace App\Presentation\Web\DataTransferObjects;

use Illuminate\Http\Request;

final class LoginDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ){}

    /**
     * Retrieves DTO attributes from a request
     * @param Request $request
     * @return self
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            email: $request->string('email')->toString(),
            password: $request->string('password')->toString(),
        );
    }
}
