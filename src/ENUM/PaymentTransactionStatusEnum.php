<?php

namespace AymanElshehawy\LaravelFawry\ENUM;

enum PaymentTransactionStatusEnum: int
{
    case PENDING = 0;
    case SUCCESS = 1;
    case FAILED = 2;
    case REFUNDED = 3;
    case CANCELED = 4;

    public static function values(): array
    {
        return array_column(self::cases(), 'value', 'name');
    }

    public static function getValue(int $key): ?string
    {
        return self::values()[$key] ?? null;
    }
} 