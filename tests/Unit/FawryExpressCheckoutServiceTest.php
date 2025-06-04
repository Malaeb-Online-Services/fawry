<?php

namespace AymanElshehawy\Fawry\Tests\Unit;

use AymanZayedElshehawy\Fawry\Exceptions\InvalidConfigurationException;
use AymanZayedElshehawy\Fawry\Exceptions\PaymentException;
use AymanZayedElshehawy\Fawry\Services\FawryExpressCheckoutService;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase;

class FawryExpressCheckoutServiceTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        Config::set('fawry.merchant_code', 'test_merchant');
        Config::set('fawry.secure_key', 'test_secure_key');
        Config::set('fawry.fawrypay_url', 'https://test.fawry.com');
    }

    public function test_it_throws_exception_when_merchant_code_is_missing()
    {
        Config::set('fawry.merchant_code', '');

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Fawry merchant code is not configured.');

        new FawryExpressCheckoutService();
    }

    public function test_it_throws_exception_when_secure_key_is_missing()
    {
        Config::set('fawry.secure_key', '');

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Fawry secure key is not configured.');

        new FawryExpressCheckoutService();
    }

    public function test_it_throws_exception_when_base_url_is_missing()
    {
        Config::set('fawry.fawrypay_url', '');

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Fawry base URL is not configured.');

        new FawryExpressCheckoutService();
    }

    public function test_it_throws_exception_when_payment_params_are_invalid()
    {
        $service = new FawryExpressCheckoutService();

        $this->expectException(PaymentException::class);

        $service->createPaymentLink([]);
    }

    public function test_it_throws_exception_when_user_data_is_invalid()
    {
        $service = new FawryExpressCheckoutService();

        $this->expectException(PaymentException::class);

        $service->createPaymentLink([
            'payment_id' => '123',
            'user' => [],
            'items' => [],
            'redirect_url' => 'https://example.com'
        ]);
    }

    public function test_it_throws_exception_when_items_are_invalid()
    {
        $service = new FawryExpressCheckoutService();

        $this->expectException(PaymentException::class);

        $service->createPaymentLink([
            'payment_id' => '123',
            'user' => [
                'id' => 'user_123',
                'phone_number' => '+1234567890',
                'email' => 'test@example.com',
                'name' => 'Test User'
            ],
            'items' => [],
            'redirect_url' => 'https://example.com'
        ]);
    }
} 