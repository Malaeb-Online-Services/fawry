<?php

namespace AymanElshehawy\Fawry\ENUM;

enum PaymentTransactionStatusEnum: string
{
    case SUCCESS = 'SUCCESS';
    case FAILED = 'FAILED';
    case PENDING = 'PENDING';
    case REFUNDED = 'REFUNDED';
    case CANCELED = 'CANCELED';

    public static function values(): array
    {
        return array_column(self::cases(), 'value', 'name');
    }

    public static function getValue(int $key): ?string
    {
        return self::values()[$key] ?? null;
    }
} 