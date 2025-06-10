<?php

namespace AymanZayedElshehawy\Fawry\Exceptions;

use Illuminate\Support\Facades\Lang;

class PaymentException extends FawryException
{
    public static function apiError(string $message, array $context = []): self
    {
        return (new self(Lang::get('fawry.exceptions.payment.api_error', ['message' => $message])))->setContext($context);
    }
} 