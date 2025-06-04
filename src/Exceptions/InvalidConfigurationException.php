<?php

namespace AymanZayedElshehawy\Fawry\Exceptions;

use Illuminate\Support\Facades\Lang;

class InvalidConfigurationException extends FawryException
{
    public static function missingMerchantCode(): self
    {
        return new self(Lang::get('fawry::exceptions.configuration.missing_merchant_code'));
    }

    public static function missingSecureKey(): self
    {
        return new self(Lang::get('fawry::exceptions.configuration.missing_secure_key'));
    }

    public static function missingBaseUrl(): self
    {
        return new self(Lang::get('fawry::exceptions.configuration.missing_base_url'));
    }
} 