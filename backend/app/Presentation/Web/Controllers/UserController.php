<?php

declare(strict_types=1);

namespace App\Presentation\Web\Controllers;

use App\Domains\Accounts\Services\UserService;
use App\Presentation\Web\Contracts\Controller;
use App\Presentation\Web\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ){}

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 20);
        $perPage = min(max(1, $perPage), 100);
        $search = $request->string('search');
        $users = $this->userService->getCustomers([
            'search' => $search,
            'per_page' => $perPage,
        ]);

        return response()->json([
            'status' => true,
            'message' => null,
            'data' => UserResource::collection($users),
        ]);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $user = $this->userService->getCustomerWithLoyaltyInfo($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
                'data' => []
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'message' => null,
            'data' => UserResource::make($user),
        ]);
    }
}
