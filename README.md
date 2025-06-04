# Laravel Fawry Payment Gateway

A Laravel package for integrating Fawry payment gateway into your Laravel applications.

## Installation

You can install the package via composer:

```bash
composer require aymanelshehawy/laravel-fawry
```

After installing the package, publish the configuration file:

```bash
php artisan vendor:publish --provider="AymanElshehawy\LaravelFawry\FawryServiceProvider" --tag="fawry-config"
```

## Configuration

Add the following variables to your `.env` file:

```env
FAWRY_MERCHANT_CODE=your_merchant_code
FAWRY_SECURE_KEY=your_secure_key
FAWRY_PAYMENT_URL=https://atfawry.fawrystaging.com
```

## Usage

### Creating a Payment Link

```php
use AymanElshehawy\LaravelFawry\Facades\Fawry;

$params = [
    'payment_id' => '123',
    'user' => [
        'id' => 'user_123',
        'phone_number_full' => '+201234567890',
        'email' => 'user@example.com',
        'first_name' => 'John',
        'last_name' => 'Doe'
    ],
    'amount' => 100.00,
    'billable_id' => 'item_123',
    'redirect' => 'https://your-domain.com/payment/callback',
    'description' => 'Payment for Order #123'
];

$paymentLink = Fawry::createPaymentLink($params);
```

### Handling Payment Status

```php
use AymanElshehawy\LaravelFawry\Facades\Fawry;

$transactionData = [
    'statusCode' => '200',
    'merchantRefNumber' => '123',
    // ... other transaction data
];

$status = Fawry::getPaymentStatus($transactionData);
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information. 