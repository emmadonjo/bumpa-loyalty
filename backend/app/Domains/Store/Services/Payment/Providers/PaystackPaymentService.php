<?php

namespace App\Domains\Store\Services\Payment\Providers;

use App\Domains\Store\Enums\PurchaseStatus;
use App\Domains\Store\Services\Payment\Contracts\PaymentInterface;
use App\Presentation\Web\DataTransferObjects\Payments\PayDto;
use App\Presentation\Web\DataTransferObjects\Payments\PayResponseDto;
use App\Presentation\Web\DataTransferObjects\Payments\TransactionDto;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class PaystackPaymentService implements PaymentInterface
{
    /**
     * @throws ConnectionException
     */
    public function pay(PayDto $payload): PayResponseDto
    {
        // convert amount to the smaller currency unit
        $amount = $payload->amount * 100;
        $frontendUrl = config('bumpa.frontend_url');

        $metadata = array_merge($payload->metadata ?? [], [
            'cancel_action' => "{$frontendUrl}/callbacks/payments/{$payload->reference}/cancel",
        ]);

        $channels = config('services.paystack.payment_channels');
        $formattedPayload = [
            'amount' => $amount,
            'email' => $payload->email,
            'currency' => $payload->currency,
            'reference' => $payload->reference,
            'channels' => $channels ? explode(',', $channels) : [],
            'callback_url' => "{$frontendUrl}/callbacks/payments/{$payload->reference}/verify",
            'metadata' => (object)$metadata,
        ];

        try {
            $response = $this->client()->post('/transaction/initialize', $formattedPayload);

            if ($response->failed()) {
                throw new Exception("Payment processing failed: {$response->body()}");
            }

            $responseData = $response->json();
            logger($responseData);
            return PayResponseDto::fromArray([
                'status' => $responseData['status'],
                'data' => [
                    'checkout_url' => $responseData['data']['authorization_url'],
                    'reference' => $responseData['data']['reference'],
                ],
                'message' => $responseData['message'] ?? null,
            ]);
        }catch (Exception $exception){
            logger("Payment processing failed: {$exception->getMessage()}", [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'exception' => $exception->getMessage(),
            ]);
            throw $exception;
        }
    }

    /**
     * verify a paystack transaction
     * @param string $reference
     * @return TransactionDto
     * @throws ConnectionException
     */
    public function verify(string $reference): TransactionDto
    {
        try {
            $response = $this->client()->get("/transaction/verify/{$reference}");
            if ($response->failed()) {
                logger($reference->body());
                throw new Exception("Payment processing failed: {$response->body()}");
            }

            $responseData = $response->json();

            return TransactionDto::fromArray([
                'status' => $responseData['status'],
                'data' => [
                    'id' => $responseData['data']['id'],
                    'status' => $responseData['data']['status'] === 'success' ? PurchaseStatus::SUCCESSFUL->value : $responseData['data']['status'],
                    'reference' => $responseData['data']['reference'],
                    'amount' => round($responseData['data']['amount']/100, 2),
                    'fees' => round($responseData['data']['fees']/100, 2),
                    'channel' => $responseData['data']['channel'],
                    'response_payload' => $responseData,
                    'currency' => $responseData['data']['currency'],
                    'metadata' => $responseData['data']['metadata'] ?? [],
                    'paid_at' => $responseData['data']['paid_at'] ?? null,
                ],
                'message' => $responseData['message'] ?? null,
            ]);
        }catch (Exception $exception){
            logger("Payment processing failed: {$exception->getMessage()}", [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'exception' => $exception->getTrace(),
            ]);
            throw $exception;
        }
    }

    private function client(): PendingRequest
    {
        return Http::acceptJson()
            ->contentType('application/json')
            ->baseUrl(config('services.paystack.base_url'))
            ->withToken(config('services.paystack.api_key'));
    }
}
