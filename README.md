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

To publish the translation files:

```bash
php artisan vendor:publish --provider="AymanElshehawy\LaravelFawry\FawryServiceProvider" --tag="fawry-translations"
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

try {
    $paymentLink = Fawry::createPaymentLink($params);
} catch (\AymanElshehawy\LaravelFawry\Exceptions\PaymentException $e) {
    // Handle payment-specific errors
    $errorContext = $e->getContext();
    // Log or handle the error
} catch (\AymanElshehawy\LaravelFawry\Exceptions\InvalidConfigurationException $e) {
    // Handle configuration errors
    // Log or handle the error
}
```

### Handling Payment Status

```php
use AymanElshehawy\LaravelFawry\Facades\Fawry;

$transactionData = [
    'statusCode' => '200',
    'merchantRefNumber' => '123',
    // ... other transaction data
];

try {
    $status = Fawry::getPaymentStatus($transactionData);
} catch (\Illuminate\Http\Client\ConnectionException $e) {
    // Handle connection errors
    // Log or handle the error
}
```

## Error Handling

The package provides several exception classes for different error scenarios:

### PaymentException

Thrown when there are issues with payment processing:
- Invalid payment parameters
- Invalid user data
- Invalid items
- API errors

```php
try {
    $paymentLink = Fawry::createPaymentLink($params);
} catch (\AymanElshehawy\LaravelFawry\Exceptions\PaymentException $e) {
    $errorContext = $e->getContext();
    // Handle the error
}
```

### InvalidConfigurationException

Thrown when there are issues with the package configuration:
- Missing merchant code
- Missing secure key
- Missing base URL

```php
try {
    $paymentLink = Fawry::createPaymentLink($params);
} catch (\AymanElshehawy\LaravelFawry\Exceptions\InvalidConfigurationException $e) {
    // Handle configuration errors
}
```

## Validation

The package validates all input parameters before making API calls. Required validations include:

- Payment ID
- User data (ID, phone number, email, name)
- Items (at least one item with valid ID and price)
- Valid URLs for redirect and webhook

## Translations

The package includes translations for error messages in both English and Arabic. To use translations:

1. Publish the translation files:
```bash
php artisan vendor:publish --provider="AymanElshehawy\LaravelFawry\FawryServiceProvider" --tag="fawry-translations"
```

2. The translations will be available in:
   - English: `resources/lang/vendor/fawry/en/fawry.php`
   - Arabic: `resources/lang/vendor/fawry/ar/fawry.php`

3. You can customize the translations by editing these files.

4. The package will automatically use the correct language based on your application's locale setting.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information. 