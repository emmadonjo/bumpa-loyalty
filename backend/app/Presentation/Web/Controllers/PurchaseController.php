<?php

declare(strict_types=1);

namespace App\Presentation\Web\Controllers;

use App\Domains\Store\Enums\PurchaseStatus;
use App\Domains\Store\Services\Payment\PaymentGateWayFactory;
use App\Domains\Store\Services\PurchaseService;
use App\Presentation\Web\Contracts\Controller;
use App\Presentation\Web\DataTransferObjects\Payments\PayDto;
use App\Presentation\Web\DataTransferObjects\PurchaseDto;
use App\Presentation\Web\Resources\PurchaseResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PurchaseController extends Controller
{
    public function __construct(
        private readonly PurchaseService $purchaseService,
    ){}

    public function purchase(Request $request): JsonResponse
    {
        // For this assessment,I'm only accepting amount from the user
        // in a production-scale app, this amount will come of the item
        // the customer wants to purchase. e.g. a product, a cart
        $request->validate([
            'amount' => 'required|numeric|min:100|max:500000',
        ]);

        $user = Auth::user();
        $gateway = PaymentGateWayFactory::make();

        $amount = $request->float('amount');
        $reference = Str::random();
        $payload = [
            'amount' => $amount,
            'email' => $user->email,
            'reference' => $reference,
            'currency' => 'NGN',
            'metadata' => [
                'customer_id' => $user->id,
            ]
        ];

        $response = $gateway->pay(PayDto::fromArray($payload));

        if (!$response->data->checkoutUrl) {
            return $this->apiError([], 'Payment link could not be generated', Response::HTTP_BAD_GATEWAY);
        }

        // persist record
        $dto = PurchaseDto::fromArray([
            'customer_id' => Auth::id(),
            'amount' => $amount,
            'purchase_date' => now()->subMinutes(random_int(1, 10_000)),
            'description' => 'Purchase of goods',
            'reference' => $reference,
            'provider' => 'paystack',
            'currency' => 'NGN',
            'request_payload' => $payload,
        ]);

        $this->purchaseService->purchase($dto);
        return $this->apiSuccess([
            'checkout_url' => $response->data->checkoutUrl,
            'reference' => $reference,
        ], 'Payment link generated');
    }

    public function verify(string $reference): JsonResponse
    {
        $purchase = $this->purchaseService->findByReference($reference);
        if (!$purchase) {
            throw new NotFoundHttpException('No payment record with the reference "' . $reference . '"');
        }

        if ($purchase->status === PurchaseStatus::SUCCESSFUL) {
            return $this->apiSuccess(PurchaseResource::make($purchase), 'Payment was successful');
        }

        if ($purchase->status !== PurchaseStatus::PENDING) {
            return $this->apiError([
                'status' => $purchase->status->value
            ], 'Payment was not successful.');
        }

        $response = $this->purchaseService->verify($purchase);

        if (!$response->status) {
            return $this->apiError([], 'Payment verification failed');
        }

        if ($response->data->status === PurchaseStatus::PENDING) {
            return $this->apiSuccess(PurchaseResource::make($purchase), 'Payment is still being processed.');
        }

        if ($response->data->status !== PurchaseStatus::SUCCESSFUL) {
            $this->purchaseService->update($purchase, [
                'status' => $response->data->status,
                'response_payload' => $response->data->responsePayload,
            ]);
            return $this->apiError([], 'Payment was not successful. Status: ' . $response->data->status->value . '.');
        }

        $this->purchaseService->update($purchase, [
            'status' => $response->data->status,
            'payment_method' => $response->data->channel,
            'fees' => $response->data->fees,
            'currency' => $response->data->currency,
            'response_payload' => $response->data->responsePayload,
            'purchased_at' => $response->data->paid_at,
        ]);

        $this->purchaseService->fireAchievementUnlocked($purchase->refresh());
        return $this->apiSuccess(PurchaseResource::make($purchase), 'Payment was successful');
    }

    public function cancel(string $reference): JsonResponse
    {
        $this->purchaseService->cancelPurchase($reference);
        return $this->apiSuccess([], 'Payment has been cancelled');
    }
}
