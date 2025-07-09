# Canva PHP SDK

A work-in-progress PHP Canva API SDK powered by [Saloon](https://github.com/saloonphp/saloon).

## Installation

```bash
composer require betterworldcollective/canva-php-sdk
```

## Authentication

Canva uses OAuth 2.0 for authenticating access to Canva Connect API. Here's how to set up and use the authentication flow using the SDK:

To begin the OAuth flow, you'll need to redirect your user to an authorization URL where they can grant your application access.
```php
use Canva\Authentications\CanvaOAuth;

$clientId = 'YOUR_CANVA_CLIENT_ID';
$clientSecret = 'YOUR_CANVA_SECRET_KEY';
$callbackUrl = 'https://app-callback-url/auth/callback';

// Generate the Blackbaud OAuth login URL
$authUrl = CanvaOAuth::getAuthUrl($clientId, $clientSecret, $callbackUrl, $codeVerifier);
```

You'll need to handle your own code verifier. The `$codeVerifier` is a random string that you generate and store securely. It is used to verify the integrity of the authorization request.

Example of generating a code verifier:
```php
function generateCodeVerifier()
{
  $randomBytes = random_bytes(32);

  return rtrim(strtr(base64_encode($randomBytes), "+/", "-_"), "=");
}
```
