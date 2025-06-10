# Laravel Fawry Package

A Laravel package for integrating with the Fawry payment gateway.

## Installation

You can install the package via composer:

```bash
composer require malaeb/fawry
```

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --provider="Malaeb\Fawry\FawryServiceProvider" --tag="fawry-config"
```

This will create a `config/fawry.php` file in your config directory.

Publish the translation files:

```bash
php artisan vendor:publish --provider="Malaeb\Fawry\FawryServiceProvider" --tag="fawry-translations"
```

## Usage

### Basic Usage

```php
use Malaeb\Fawry\Facades\Fawry;

// Create a payment
$payment = Fawry::createPayment([
    'payment_id' => '123',
    'user' => [
        'id' => '1',
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone_number' => '01234567890'
    ],
    'items' => [
        [
            'id' => '1',
            'price' => 100,
            'quantity' => 1
        ]
    ],
    'redirect_url' => 'https://your-domain.com/payment/callback'
]);

// Get payment URL
$paymentUrl = $payment->getPaymentUrl();

// Handle webhook
$webhook = Fawry::handleWebhook($request->all());
```

### Error Handling

```php
try {
    $payment = Fawry::createPayment([
        'payment_id' => '123',
        'user' => [
            'id' => '1',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone_number' => '01234567890'
        ],
        'items' => [
            [
                'id' => '1',
                'price' => 100,
                'quantity' => 1
            ]
        ],
        'redirect_url' => 'https://your-domain.com/payment/callback'
    ]);
} catch (\Malaeb\Fawry\Exceptions\PaymentException $e) {
    // Handle payment validation errors
    return $e->getMessage();
} catch (\Malaeb\Fawry\Exceptions\InvalidConfigurationException $e) {
    // Handle configuration errors
    return $e->getMessage();
}
```

### Webhook Handling

```php
use Malaeb\Fawry\Facades\Fawry;

public function handleWebhook(Request $request)
{
    try {
        $webhook = Fawry::handleWebhook($request->all());
        
        // Process the webhook
        if ($webhook->isSuccess()) {
            // Payment was successful
            return response()->json(['message' => 'Payment successful']);
        } else {
            // Payment failed
            return response()->json(['message' => 'Payment failed']);
        }
    } catch (\Malaeb\Fawry\Exceptions\PaymentException $e) {
        // Handle payment validation errors
        return response()->json(['error' => $e->getMessage()], 400);
    } catch (\Malaeb\Fawry\Exceptions\InvalidConfigurationException $e) {
        // Handle configuration errors
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
```

## Configuration

After publishing the configuration file, you can set your Fawry credentials in the `config/fawry.php` file:

```php
return [
    'merchant_code' => env('FAWRY_MERCHANT_CODE'),
    'secure_key' => env('FAWRY_SECURE_KEY'),
    'base_url' => env('FAWRY_BASE_URL', 'https://atfawry.fawrystaging.com/'),
];
```

## Translations

The package includes translations for error messages. You can publish them using:

```bash
php artisan vendor:publish --provider="Malaeb\Fawry\FawryServiceProvider" --tag="fawry-translations"
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information. 