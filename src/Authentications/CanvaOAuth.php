<?php

namespace Canva\Authentications;

use Canva\Canva;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Traits\OAuth2\AuthorizationCodeGrant;
use Saloon\Http\OAuth2\GetAccessTokenRequest;
use Saloon\Http\Request;
use Saloon\Enums\Method;
use Saloon\Contracts\Body\HasBody;
use Saloon\Traits\Body\HasFormBody;
use Saloon\Traits\Plugins\AcceptsJson;

class CanvaOAuth extends Canva
{
    use AuthorizationCodeGrant;

    private ?string $codeVerifier = null;
    private ?string $codeChallenge = null;

    public function __construct(
        string $clientId,
        string $clientSecret,
        string $redirectUri,
    ) {
        $this->oauthConfig()
            ->setClientId($clientId)
            ->setDefaultScopes(['asset:read', 'asset:write', 'design:content:read', 'design:content:write', 'design:meta:read', 'brandtemplate:content:read', 'brandtemplate:meta:read', 'profile:read'])
            ->setClientSecret($clientSecret)
            ->setRedirectUri($redirectUri);
    }

    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()
            ->setTokenEndpoint('https://www.canva.com/api/v1/oauth2/token')
            ->setAuthorizeEndpoint('https://www.canva.com/api/oauth/authorize');
    }

    /**
     * Generate a random code verifier for PKCE
     *
     * @return string
     */
    private function generateCodeVerifier(): string
    {
        $randomBytes = random_bytes(32);
        return rtrim(strtr(base64_encode($randomBytes), '+/', '-_'), '=');
    }

    /**
     * Generate code challenge from code verifier using SHA256
     *
     * @param string $codeVerifier
     * @return string
     */
    private function generateCodeChallenge(string $codeVerifier): string
    {
        $hash = hash('sha256', $codeVerifier, true);
        return rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');
    }

    /**
     * Get the authorization URL with PKCE parameters
     *
     * @param string|null $state
     * @return string
     */
    public function getAuthUrl(?string $state = null): string
    {
        // Generate PKCE parameters
        $this->codeVerifier = $this->generateCodeVerifier();
        $this->codeChallenge = $this->generateCodeChallenge($this->codeVerifier);

        // Get the base authorization URL without scopes
        return $this->getAuthorizationUrl(
            additionalQueryParameters: [
                'code_challenge' => $this->codeChallenge,
                'code_challenge_method' => 'S256'
            ]
        );
    }
}