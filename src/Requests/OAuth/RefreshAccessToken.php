<?php

namespace Canva\Requests\OAuth;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasFormBody;

class RefreshAccessToken extends Request implements HasBody
{
    use HasFormBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected OAuthConfig $oauthConfig,
        protected string $refreshToken,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/v1/oauth/token';
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
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refreshToken,
            'client_id' => $this->oauthConfig->getClientId(),
            'client_secret' => $this->oauthConfig->getClientSecret(),
        ];
    }

}
