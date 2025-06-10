<?php

namespace Malaeb\Fawry\DTOs;

use Malaeb\Fawry\ENUM\PaymentTransactionStatusEnum;

class ReturnPaymentHandleWebhookDTO
{
    public function __construct(
        public readonly PaymentTransactionStatusEnum $status,
        public readonly string $status_text,
        public readonly ?string $message = null,
        public readonly ?array $response = null
    ) {}

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'status_text' => $this->status_text,
            'message' => $this->message,
            'response' => $this->response,
        ];
    }
} 