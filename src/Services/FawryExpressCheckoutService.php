<?php

namespace AymanElshehawy\LaravelFawry\Services;

use AymanElshehawy\LaravelFawry\DTOs\ReturnPaymentHandleWebhookDTO;
use AymanElshehawy\LaravelFawry\ENUM\PaymentTransactionStatusEnum;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;

class FawryExpressCheckoutService
{
    protected string $merchantCode;
    protected string $secureKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->merchantCode = config('fawry.merchant_code');
        $this->secureKey = config('fawry.secure_key');
        $this->baseUrl = config('fawry.fawrypay_url');
    }

    /**
     * Generate a new hosted payment link
     */
    public function createPaymentLink(array $params): ?string
    {
        $merchantRefNumber = $params['payment_id'];
        $customerProfileId = $params['user']['id'];
        $amount = $params['amount'];
        $itemId = $params['billable_id'];
        $returnUrl = $params['redirect'];
        $signature = $this->generateSignature($merchantRefNumber, $customerProfileId, $amount, $returnUrl, $itemId);
        $payload = [
            'merchantCode' => $this->merchantCode,
            'merchantRefNum' => $merchantRefNumber,
            'customerMobile' => $params['user']['phone_number_full'],
            'customerEmail' => $params['user']['email'],
            'customerName' => $params['user']['first_name'] . ' ' . $params['user']['last_name'],
            'customerProfileId' => $customerProfileId,
            'language' => (App::getLocale() == 'en') ? 'en-gb' : 'ar-eg',
            'paymentExpiry' => now()->addMinutes(30)->timestamp * 1000,
            'chargeItems' => [
                [
                    'itemId' => $itemId,
                    'description' => $params['description'] ?? 'Payment for Order',
                    'price' => number_format($amount, 2, '.', ''),
                    'quantity' => 1
                ],
            ],
            'returnUrl' => $returnUrl,
            'authCaptureModePayment' => false,
            'signature' => $signature,
        ];

        $response = Http::timeout(20)->post("{$this->baseUrl}/fawrypay-api/api/payments/init", $payload);

        if ($response->successful()) {
            return $response->body();
        }
        return null;
    }

    /**
     * Generate Fawry signature for request
     */
    protected function generateSignature(string $merchantRefNumber, string $customerProfileId, float $amount, $returnUrl, $itemId): string
    {
        $string = $this->merchantCode .
            $merchantRefNumber .
            $customerProfileId .
            $returnUrl .
            $itemId .
            '1' .
            number_format($amount, 2, '.', '') .
            $this->secureKey;
        return hash('sha256', $string);
    }

    /**
     * @throws ConnectionException
     */
    public function getPaymentStatus(array $transactionData): ReturnPaymentHandleWebhookDTO
    {
        if (isset($transactionData['statusCode']) && (int)$transactionData['statusCode'] == 200) {
            $merchantRefNumber = $transactionData['merchantRefNumber'] ?? '';
            $merchantCode = $this->merchantCode;
            $merchantSecureKey = $this->secureKey;
            $signature = hash('sha256', $merchantCode . $merchantRefNumber . $merchantSecureKey);
            $url = $this->baseUrl . '/ECommerceWeb/Fawry/payments/status/v2' .
                '?merchantCode=' . $merchantCode .
                '&merchantRefNumber=' . $merchantRefNumber .
                '&signature=' . $signature;
            $response = Http::get($url);
            $response = $response->object();
            return new ReturnPaymentHandleWebhookDTO(
                status: ($this->isPaidSuccessfully($response->orderStatus)) ? PaymentTransactionStatusEnum::SUCCESS : PaymentTransactionStatusEnum::FAILED,
                status_text: $response->orderStatus,
                response: $transactionData
            );
        } else {
            return new ReturnPaymentHandleWebhookDTO(
                status: PaymentTransactionStatusEnum::FAILED,
                status_text: PaymentTransactionStatusEnum::FAILED->name,
                message: $transactionData['statusDescription'] ?? 'Payment failed',
                response: $transactionData
            );
        }
    }

    protected function isPaidSuccessfully(string $status): bool
    {
        return $status === 'PAID';
    }
} 