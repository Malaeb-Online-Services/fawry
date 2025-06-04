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
        'phone_number' => '+201234567890',
        'email' => 'user@example.com',
        'name' => 'John Doe'
    ],
    'items' => [
        [
            'id' => 'item_1',
            'description' => 'Product 1',
            'price' => 50.00,
            'quantity' => 1
        ],
        [
            'id' => 'item_2',
            'description' => 'Product 2',
            'price' => 50.00,
            'quantity' => 1
        ]
    ],
    'redirect_url' => 'https://your-domain.com/payment/callback',
    'webhook_url' => 'https://your-domain.com/payment/webhook'
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