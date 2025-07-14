<?php

namespace Canva\Authentications;

use Canva\Canva;
use Saloon\Contracts\Body\HasBody;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Traits\Body\HasFormBody;
use Saloon\Traits\OAuth2\AuthorizationCodeGrant;

class CanvaOAuth extends Canva implements HasBody
{
    use AuthorizationCodeGrant, HasFormBody;

    private ?string $codeVerifier = null;
    private ?string $codeChallenge = null;

    public function __construct(
        string $clientId,
        string $clientSecret,
        string $redirectUri,
        ?string $codeVerifier = null
    ) {
        $this->oauthConfig()
            ->setClientId($clientId)
            ->setDefaultScopes(['asset:read', 'asset:write', 'design:content:read', 'design:content:write', 'design:meta:read', 'brandtemplate:content:read', 'brandtemplate:meta:read', 'profile:read'])
            ->setClientSecret($clientSecret)
            ->setRedirectUri($redirectUri);

        if ($codeVerifier !== null) {
            $this->setCodeChallenge($codeVerifier);
        }
    }

    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()
            ->setTokenEndpoint('https://api.canva.com/rest/v1/oauth/token')
            ->setAuthorizeEndpoint('https://www.canva.com/api/oauth/authorize');
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
        // Get the base authorization URL without scopes
        return $this->getAuthorizationUrl(
            additionalQueryParameters: [
                'code_challenge' => $this->codeChallenge,
                'code_challenge_method' => 'S256'
            ]
        );
    }

    public function setCodeChallenge(string $codeVerifier): void
    {
        $this->codeVerifier = $codeVerifier;
        $this->codeChallenge = $this->generateCodeChallenge($this->codeVerifier);
    }
}
