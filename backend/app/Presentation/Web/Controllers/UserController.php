<?php

declare(strict_types=1);

namespace App\Presentation\Web\Controllers;

use App\Domains\Accounts\Services\UserService;
use App\Presentation\Web\Contracts\Controller;
use App\Presentation\Web\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ){}

    public function index(Request $request): JsonResponse
    {
        Gate::authorize('isAdmin');

        $perPage = $request->integer('per_page', 20);
        $perPage = min(max(1, $perPage), 100);
        $search = $request->string('search');
        $users = $this->userService->getCustomers([
            'search' => $search,
            'per_page' => $perPage,
        ]);

        return $this->apiSuccess(UserResource::collection($users));
    }

    public function show(int $id): JsonResponse
    {
        $user = Auth::user();
        abort_unless(
            $user->id === $id || $user->isAdmin(),
            Response::HTTP_FORBIDDEN,
            'Forbidden'
        );

        $user = $this->userService->getCustomerWithLoyaltyInfo($id);

        if (!$user) {
            return $this->apiError([],'User not found',Response::HTTP_NOT_FOUND);
        }

        return $this->apiSuccess(UserResource::make($user));
    }
}
