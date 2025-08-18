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

## Using Access Tokens
Once you have the access token, you can use it to make authenticated requests to the Canva API. The SDK provides a convenient way to include the access token in your requests.
```php
use Saloon\Http\Auth\AccessTokenAuthenticator;

// Set up token configuration for requests
$canva->authenticateWithToken($token, $refreshToken, $expiresAt);
```

## Token Management

### Revoking Access Tokens

The SDK provides functionality to revoke access tokens when they're no longer needed or when you want to invalidate user access. This is useful for:

- Logging users out of your application
- Revoking access when users remove your integration
- Cleaning up expired or compromised tokens
- Implementing security measures

```php
use Canva\Requests\OAuth\RevokeAccessToken;
use Saloon\Helpers\OAuth2\OAuthConfig;

// Create OAuth config (you can reuse the same config from authentication)
$oauthConfig = new OAuthConfig(
    clientId: $config["client_id"],
    clientSecret: $config["client_secret"],
    redirectUri: $config["redirect_uri"]
);

// Revoke a specific access token
$revokeRequest = new RevokeAccessToken(
    accessToken: $userAccessToken, // The token you want to revoke
    oauthConfig: $oauthConfig
);

// Send the request using Saloon
$response = $revokeRequest->send();
```

### Getting User Profile Information

The SDK allows you to retrieve user profile information using the access token obtained during OAuth authentication. This is useful for:

- Displaying user information in your application
- Personalizing the user experience
- Storing user details in your database
- Building user dashboards

```php
$connector->user()->profile();
```

**Note**: Currently, this endpoint returns the display name of the user account associated with the provided access token. More user information is expected to be included in future API updates.

## Designs

### Creating a Design

To create a design using the Canva API, you can use the `CreateDesign` request. This request allows you to specify the design type, dimensions, and other parameters.

```php
use Canva\Requests\Designs\CreateDesign;

// Create a new design
// Refer to the Canva API documentation for available design types and parameters
// https://www.canva.dev/docs/connect/api-reference/designs/create-design/
$connector->designs()->create([
    "design_type" => [
        "type" => "custom",
        "height" => 1080,
        "width" => 1920,
    ],
    "title" => "My New Design"
]);
```

### Create Design Export Job
To create an export job for a design, you can use the `CreateExportJob` request. This allows you to specify the design ID and the desired export format.

```php
use Canva\Requests\Export\CreateExportJob;

// Create an export job for a design
// Refer to the Canva API documentation https://www.canva.dev/docs/connect/api-reference/exports/create-design-export-job/
$connector->designExportJob()->create([
    "design_id" => "YOUR_DESIGN_ID",
    "format" => [
        "type" => "jpg", // See ExportFormatType enum
        "quality" => 80, // Optional
    ]
]);
```


### Get Design Export Job
To check the status of an export job, you can use the `GetDesignExportJob`
```php
$connector->designExportJob->get(
    exportId: "YOUR_EXPORT_JOB_ID" // Replace with your export job ID
)
```
