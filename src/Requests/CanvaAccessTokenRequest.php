<?php

namespace Canva\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasFormBody;

class CanvaAccessTokenRequest extends Request implements HasBody
{
    use HasFormBody;

    protected Method $method = Method::POST;

    /**
     * Create a new CanvaAccessTokenRequest instance.
     *
     * @param string $code The authorization code received from the OAuth flow.
     * @param OAuthConfig $oauthConfig The OAuth configuration containing client ID, secret, and redirect URI.
     * @param string $codeVerifier The code verifier used in the PKCE flow.
     */
    public function __construct(
        protected string $code,
        protected OAuthConfig $oauthConfig,
        protected string $codeVerifier
    ) {
        //
    }

    /**
     * Resolve the endpoint for the request.
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return 'https://api.canva.com/rest/v1/oauth/token'; // Double-check Canva’s docs for exact URL
    }

    /**
     * Get the headers for the request.
     *
     * @return array<string, string>
     */
    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
    }

    /**
     * Get the body of the request.
     *
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'grant_type' => 'authorization_code',
            'client_id' => $this->oauthConfig->getClientId(),
            'client_secret' => $this->oauthConfig->getClientSecret(),
            'redirect_uri' => $this->oauthConfig->getRedirectUri(),
            'code' => $this->code,
            'code_verifier' => $this->codeVerifier,
        ];
    }
}
