<?php

namespace Canva;

use Canva\Requests\OAuth\CanvaAccessToken;
use Canva\Requests\OAuth\RefreshAccessToken;
use Canva\Resources\DesignExportJobResource;
use Canva\Resources\DesignResource;
use Canva\Resources\KeyResource;
use Canva\Resources\UserResource;
use Canva\Traits\VerifiesToken;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Traits\OAuth2\AuthorizationCodeGrant;

class Canva extends Connector
{
    use AuthorizationCodeGrant;
    use VerifiesToken;

    private ?string $codeVerifier = null;
    private ?string $codeChallenge = null;

    /**
     * Create a new Canva instance.
     *
     * @param string $clientId The client ID for the OAuth application.
     * @param string $clientSecret The client secret for the OAuth application.
     * @param string $redirectUri The redirect URI for the OAuth application.
     */
    public function __construct(
        string $clientId,
        string $clientSecret,
        string $redirectUri,
    ) {
        $this->oauthConfig()
            ->setClientId($clientId)
            ->setClientSecret($clientSecret)
            ->setRedirectUri($redirectUri);
    }

    /**
     * Resolve the base URL for the Canva API.
     *
     * @return OAuthConfig
     */
    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()
            ->setAuthorizeEndpoint('https://www.canva.com/api/oauth/authorize')
            ->setDefaultScopes([
                'asset:read',
                'asset:write',
                'design:content:read',
                'design:content:write',
                'design:meta:read',
                'brandtemplate:content:read',
                'brandtemplate:meta:read',
                'profile:read'
            ]);
    }

    /**
     * The default headers for the Canva API requests.
     *
     * @return array<string, string>
     */
    public function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
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
     * Get the authorization URL for the OAuth flow.
     *
     * @param string|null $state Optional state parameter to maintain state between request and callback.
     * @param array<string, mixed> $additionalQuery Additional query parameters to include in the authorization URL.
     * @return string
     */
    public function getAuthUrl(?string $state = null, array $additionalQuery = []): string
    {
        // Get the base authorization URL without scopes
        return $this->getAuthorizationUrl(
            state: $state,
            additionalQueryParameters: [
                'code_challenge' => $this->codeChallenge,
                'code_challenge_method' => 'S256',
                ...$additionalQuery,
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

    /**
     * Get the code verifier used in the PKCE flow.
     *
     * @return self
     */
    public function setCodeVerifier(string $codeVerifier): self
    {
        $this->setCodeChallenge($codeVerifier);

        return $this;
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

        return new CanvaAccessToken($code, $oauthConfig, $this->codeVerifier);
    }

    protected function resolveRefreshTokenRequest(OAuthConfig $oauthConfig, string $refreshToken): Request
    {
        return new RefreshAccessToken($oauthConfig, $refreshToken);
    }

    /**
     * The base URL for the Canva API.
     *
     * @return string
     */
    public function resolveBaseUrl(): string
    {
        return 'https://api.canva.com/rest/';
    }

    public function authenticateWithToken(string $token): static
    {
        $authenticator = new TokenAuthenticator($token);

        return $this->authenticate($authenticator);
    }

    public function design(): DesignResource
    {
        return new DesignResource($this);
    }

    public function designExportJob(): DesignExportJobResource
    {
        return new DesignExportJobResource($this);
    }

    public function user(): UserResource
    {
        return new UserResource($this);
    }

    public function keys(): KeyResource
    {
        return new KeyResource($this);
    }
}
