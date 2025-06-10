<?php

namespace AymanZayedElshehawy\Fawry\Exceptions;

class PaymentException extends FawryException
{
    public static function apiError(string $message, array $context = []): self
    {
        return (new self(__('fawry.exceptions.payment.api_error', ['message' => $message])))->setContext($context);
    }
} 