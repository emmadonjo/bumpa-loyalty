<?php

namespace App\Presentation\Web\Controllers;

use App\Domains\Loyalty\Services\UserAchievementService;
use App\Presentation\Web\Resources\UserAchievementResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserAchievementController
{
    public function __construct(
        private readonly UserAchievementService $userAchievementService,
    ){}

    public function index(Request $request): JsonResponse
    {
        $params = $this->getParams($request);
        $achievements = $this->userAchievementService->get($params);
        return response()->json([
            'success' => true,
            'message' => null,
            'data' => UserAchievementResource::collection($achievements),
        ]);
    }

    /**
     * Retrieve user achievements
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function userAchievements(Request $request, int $id): JsonResponse
    {
        $userId = Auth::id();
        abort_unless($userId === $id, Response::HTTP_FORBIDDEN, 'Forbidden');

        $params = $this->getParams($request);
        $params['user_id'] = $userId;
        $achievements = $this->userAchievementService->get($params);
        return response()->json([
            'success' => true,
            'message' => null,
            'data' => UserAchievementResource::collection($achievements),
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getParams(Request $request): array
    {
        $perPage = $request->integer('per_page', 20);
        $params['per_page'] = min(max(1, $perPage), 100);
        $params['search'] = $request->string('search');
        $params['type'] = $request->query('type');
        $params['user_id'] = $request->query('user_id');
        return $params;
    }
}
