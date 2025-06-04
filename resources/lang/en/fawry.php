<?php

return [
    'exceptions' => [
        'configuration' => [
            'missing_merchant_code' => 'Fawry merchant code is not configured.',
            'missing_secure_key' => 'Fawry secure key is not configured.',
            'missing_base_url' => 'Fawry base URL is not configured.',
        ],
        'payment' => [
            'invalid_items' => 'Payment items are invalid or missing.',
            'invalid_amount' => 'Payment amount is invalid.',
            'invalid_customer_data' => 'Customer data is invalid or missing.',
            'api_error' => 'Fawry API Error: :message',
            'required_field_missing' => "Required field ':field' is missing",
            'required_user_field_missing' => "Required user field ':field' is missing or empty",
            'invalid_redirect_url' => 'Invalid redirect URL format',
            'invalid_webhook_url' => 'Invalid webhook URL format',
            'items' => [
                'required' => 'At least one item is required',
                'invalid' => 'Item must have a valid id and price',
                'invalid_quantity' => 'Item quantity must be a positive number',
            ],
        ],
    ],
]; 