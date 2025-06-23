<?php

namespace Malaeb\Fawry\Services;

use Malaeb\Fawry\DTOs\ReturnPaymentHandleWebhookDTO;
use Malaeb\Fawry\ENUM\PaymentTransactionStatusEnum;
use Malaeb\Fawry\Exceptions\InvalidConfigurationException;
use Malaeb\Fawry\Exceptions\PaymentException;
use Malaeb\Fawry\Validation\PaymentValidation;
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

        $this->validateConfiguration();
    }

    protected function validateConfiguration(): void
    {
        if (empty($this->merchantCode)) {
            throw InvalidConfigurationException::missingMerchantCode();
        }

        if (empty($this->secureKey)) {
            throw InvalidConfigurationException::missingSecureKey();
        }

        if (empty($this->baseUrl)) {
            throw InvalidConfigurationException::missingBaseUrl();
        }
    }

    /**
     * Generate a new hosted payment link
     *
     * @param array $params
     * @return string|null
     * @throws PaymentException
     */
    public function createPaymentLink(array $params): ?string
    {
        try {
            PaymentValidation::validatePaymentParams($params);

            $merchantRefNumber = $params['payment_id'];
            $customerProfileId = $params['user']['id'];
            
            // Prepare charge items
            $chargeItems = [];

            foreach ($params['items'] as $item) {
                $chargeItems[] = [
                    'itemId' => $item['id'],
                    'description' => $item['description'] ?? 'Payment for Order',
                    'price' => number_format($item['price'], 2, '.', ''),
                    'quantity' => $item['quantity'] ?? 1
                ];
            }

            $signature = $this->generateSignature($merchantRefNumber, $params['redirect_url'], $chargeItems, $customerProfileId);
            
            $payload = [
                'merchantCode' => $this->merchantCode,
                'merchantRefNum' => $merchantRefNumber,
                'customerMobile' => $params['user']['phone_number'],
                'customerEmail' => $params['user']['email'],
                'customerName' => $params['user']['name'],
                'customerProfileId' => $customerProfileId,
                'language' => (App::getLocale() == 'en') ? 'en-gb' : 'ar-eg',
                'paymentExpiry' => now()->addMinutes(30)->timestamp * 1000,
                'chargeItems' => $chargeItems,
                'returnUrl' => $params['redirect_url'],
                'orderWebHookUrl' => $params['webhook_url'] ?? null,
                'authCaptureModePayment' => false,
                'signature' => $signature,
            ];

            // Log the payload for debugging
            \Log::info('Fawry Payment Payload', $payload);

            $response = Http::post("{$this->baseUrl}/fawrypay-api/api/payments/init", $payload);

            if ($response->successful()) {
                return $response->body();
            }

            throw PaymentException::apiError($response->json()['description'] ?? 'Unknown error', [
                'response' => $response->json()
            ]);
        } catch (ConnectionException $exception) {
            // Log the connection error
            \Log::error('Fawry Payment Connection Error', ['message' => $exception->getMessage()]);
            throw PaymentException::apiError($exception->getMessage(), [
                'original_error' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Generate Fawry signature for request
     */
    protected function generateSignature(string $merchantRefNumber, string $returnUrl, array $items, ?string $customerProfileId): string
    {
        // Step 1: Sort items by itemId (as strings)
        usort($items, function ($a, $b) {
            return strcmp($a['itemId'], $b['itemId']);
        });

        // Step 2: Concatenate sorted item details
        $itemsString = '';
        foreach ($items as $item) {
            $itemsString .= $item['itemId'];
            $itemsString .= $item['quantity'];
            $itemsString .= number_format($item['price'], 2, '.', '');
        }

        // Step 3: Build the full string to hash
        $stringToHash =
            $this->merchantCode .
            $merchantRefNumber .
            ($customerProfileId ?? '') . // use empty string if null
            $returnUrl .
            $itemsString .
            $this->secureKey;

        // Step 4: Return SHA-256 hash
        return hash('sha256', $stringToHash);
    }

    /**
     * @throws ConnectionException
     */
    public function getPaymentStatus(array $transactionData): ReturnPaymentHandleWebhookDTO
    {
        try {
            if (!empty($transactionData['merchantRefNumber'])) {
                $merchantRefNumber = $transactionData['merchantRefNumber'];
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
                    status: (isset($response->orderStatus) && $this->isPaidSuccessfully($response->orderStatus)) ? PaymentTransactionStatusEnum::SUCCESS : PaymentTransactionStatusEnum::FAILED,
                    status_text: $response->orderStatus,
                    response: $transactionData
                );
            }

            return new ReturnPaymentHandleWebhookDTO(
                status: PaymentTransactionStatusEnum::FAILED,
                status_text: PaymentTransactionStatusEnum::FAILED->name,
                message: $transactionData['statusDescription'] ?? 'Payment failed',
                response: $transactionData
            );
        } catch (ConnectionException $exception) {
            throw $exception;
        }
    }

    protected function isPaidSuccessfully(string $status): bool
    {
        return $status === 'PAID';
    }
} 