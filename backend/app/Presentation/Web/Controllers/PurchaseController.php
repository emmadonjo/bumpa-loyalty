<?php

declare(strict_types=1);

namespace App\Presentation\Web\Controllers;

use App\Domains\Store\Services\PurchaseService;
use App\Presentation\Web\Contracts\Controller;
use App\Presentation\Web\DataTransferObjects\PurchaseDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
class PurchaseController extends Controller
{
    public function __construct(
        private readonly PurchaseService $purchaseService,
    ){}

    public function purchase(): JsonResponse
    {
        $dto = PurchaseDto::fromArray([
           'customer_id' => Auth::id(),
           'amount' => random_int(1_000, 5_0000),
            'purchase_date' => now()->subMinutes(random_int(1, 10_000)),
            'description' => 'Purchase of goods',
        ]);

        $this->purchaseService->purchase($dto);
        return $this->apiSuccess([], 'Purchase successful');
    }
}
