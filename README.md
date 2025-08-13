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

$config = [
  "client_id" => "YOUR_CLIENT_ID",
  "client_secret" => "YOUR_CLIENT_SECRET",
  "redirect_uri" => "YOUR_REDIRECT_URI",
];

// Generate the Canva OAuth login URL
$canva = new CanvaOAuth(
  clientId: $config["client_id"],
  clientSecret: $config["client_secret"],
  redirectUri: $config["redirect_uri"]
);

$canva->setCodeChallenge($codeVerifier); 

$authorizationUrl = $canva->getAuthUrl();
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

After the user grants access, they will be redirected back to your specified redirect URI with a `code` parameter. You can then exchange this code for an access token.

```php
$canva = new CanvaOAuth(
  clientId: $config["client_id"],
  clientSecret: $config["client_secret"],
  redirectUri: $config["redirect_uri"],
);

$canva->setCodeVerifier($codeVerifier); // Use the same code verifier you generated earlier

// `code` and `state` are parameters returned by Canva after the user grants access
$authenticator = $canva->getAccessToken($request["code"], $request["state"]); // Store values securely
```

## Token Management

### Revoking Access Tokens

The SDK provides functionality to revoke access tokens when they're no longer needed or when you want to invalidate user access. This is useful for:

- Logging users out of your application
- Revoking access when users remove your integration
- Cleaning up expired or compromised tokens
- Implementing security measures

```php
use Canva\Requests\OAuth\RevokeAccessTokenRequest;
use Saloon\Helpers\OAuth2\OAuthConfig;

// Create OAuth config (you can reuse the same config from authentication)
$oauthConfig = new OAuthConfig(
    clientId: $config["client_id"],
    clientSecret: $config["client_secret"],
    redirectUri: $config["redirect_uri"]
);

// Revoke a specific access token
$revokeRequest = new RevokeAccessTokenRequest(
    accessToken: $userAccessToken, // The token you want to revoke
    oauthConfig: $oauthConfig
);

// Send the request using Saloon
$response = $revokeRequest->send();
```