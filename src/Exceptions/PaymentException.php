<?php

namespace AymanZayedElshehawy\Fawry\Exceptions;

use Illuminate\Support\Facades\Lang;

class PaymentException extends FawryException
{
    public static function invalidItems(): self
    {
        return new self(\trans('fawry::exceptions.payment.invalid_items'));
    }

    public static function invalidAmount(): self
    {
        return new self(\trans('fawry::exceptions.payment.invalid_amount'));
    }

    public static function invalidCustomerData(): self
    {
        return new self(\trans('fawry::exceptions.payment.invalid_customer_data'));
    }

    public static function apiError(string $message, array $context = []): self
    {
        return (new self(\trans('fawry::exceptions.payment.api_error', ['message' => $message])))->setContext($context);
    }
} 