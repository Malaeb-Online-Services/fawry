<?php

namespace AymanElshehawy\Fawry\Validation;

use AymanElshehawy\LaravelFawry\Exceptions\PaymentException;

class PaymentValidation
{
    public static function validatePaymentParams(array $params): void
    {
        self::validateRequiredFields($params);
        self::validateUserData($params['user'] ?? []);
        self::validateItems($params['items'] ?? []);
        self::validateUrls($params);
    }

    protected static function validateRequiredFields(array $params): void
    {
        $requiredFields = ['payment_id', 'user', 'items', 'redirect_url'];
        
        foreach ($requiredFields as $field) {
            if (!isset($params[$field])) {
                throw PaymentException::invalidCustomerData()->setContext([
                    'field' => $field,
                    'message' => \trans('fawry::exceptions.payment.required_field_missing', ['field' => $field])
                ]);
            }
        }
    }

    protected static function validateUserData(array $userData): void
    {
        $requiredFields = ['id', 'phone_number', 'email', 'name'];
        
        foreach ($requiredFields as $field) {
            if (!isset($userData[$field]) || empty($userData[$field])) {
                throw PaymentException::invalidCustomerData()->setContext([
                    'field' => $field,
                    'message' => \trans('fawry::exceptions.payment.required_user_field_missing', ['field' => $field])
                ]);
            }
        }
    }

    protected static function validateItems(array $items): void
    {
        if (empty($items)) {
            throw PaymentException::invalidItems()->setContext([
                'message' => \trans('fawry::exceptions.payment.items.required')
            ]);
        }

        foreach ($items as $index => $item) {
            if (!isset($item['id'], $item['price']) || empty($item['id']) || !is_numeric($item['price'])) {
                throw PaymentException::invalidItems()->setContext([
                    'index' => $index,
                    'message' => \trans('fawry::exceptions.payment.items.invalid')
                ]);
            }

            if (isset($item['quantity']) && (!is_numeric($item['quantity']) || $item['quantity'] < 1)) {
                throw PaymentException::invalidItems()->setContext([
                    'index' => $index,
                    'message' => \trans('fawry::exceptions.payment.items.invalid_quantity')
                ]);
            }
        }
    }

    protected static function validateUrls(array $params): void
    {
        if (!filter_var($params['redirect_url'], FILTER_VALIDATE_URL)) {
            throw PaymentException::invalidCustomerData()->setContext([
                'field' => 'redirect_url',
                'message' => \trans('fawry::exceptions.payment.invalid_redirect_url')
            ]);
        }

        if (isset($params['webhook_url']) && !filter_var($params['webhook_url'], FILTER_VALIDATE_URL)) {
            throw PaymentException::invalidCustomerData()->setContext([
                'field' => 'webhook_url',
                'message' => \trans('fawry::exceptions.payment.invalid_webhook_url')
            ]);
        }
    }
} 