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

    public function __construct(
        protected string $code,
        protected OAuthConfig $oauthConfig,
        protected string $codeVerifier
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return 'https://api.canva.com/rest/v1/oauth/token'; // Double-check Canva’s docs for exact URL
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
    }

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
