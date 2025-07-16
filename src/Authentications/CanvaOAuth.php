<?php

namespace Canva\Authentications;

use Canva\Canva;
use Canva\Requests\CanvaAccessTokenRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Request;
use Saloon\Traits\OAuth2\AuthorizationCodeGrant;

class CanvaOAuth extends Canva
{
    use AuthorizationCodeGrant;

    private ?string $codeVerifier = null;
    private ?string $codeChallenge = null;

    /**
     * Create a new CanvaOAuth instance.
     *
     * @param string $clientId The client ID for the OAuth application.
     * @param string $clientSecret The client secret for the OAuth application.
     * @param string $redirectUri The redirect URI for the OAuth application.
     * @param string|null $codeVerifier Optional code verifier for PKCE flow.
     */
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

    /**
     * Resolve the base URL for the Canva API.
     *
     * @return OAuthConfig
     */
    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()->setAuthorizeEndpoint('https://www.canva.com/api/oauth/authorize');
    }

    /**
     * Resolve the access token request for the OAuth flow.
     *
     * @param string $code The authorization code received from the OAuth flow.
     * @param OAuthConfig $oauthConfig The OAuth configuration containing client ID, secret, and redirect URI.
     * @return Request
     */
    protected function resolveAccessTokenRequest(string $code, OAuthConfig $oauthConfig): Request
    {
        if (empty($this->codeVerifier)) {
            throw new \InvalidArgumentException('Code verifier must not be empty when using PKCE.');
        }

        return new CanvaAccessTokenRequest($code, $oauthConfig, $this->codeVerifier);
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

    /**
     * Set the code verifier and generate the code challenge.
     *
     * @param string $codeVerifier The code verifier used in the PKCE flow.
     */
    public function setCodeChallenge(string $codeVerifier): void
    {
        $this->codeVerifier = $codeVerifier;
        $this->codeChallenge = $this->generateCodeChallenge($this->codeVerifier);
    }
}
