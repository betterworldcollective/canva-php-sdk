<?php

namespace Canva\Requests\OAuth;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasFormBody;

/**
 * RevokeAccessTokenRequest
 *
 * Revoke an access token or a refresh token.
 *
 * If you revoke a _refresh token_, be aware that:
 *
 * - The
 * refresh token's lineage is also revoked. This means that access tokens created from that refresh
 * token are also revoked.
 * - The user's consent for your integration is also revoked. This means that
 * the user must go through the OAuth process again to use your integration.
 *
 * Requests to this endpoint
 * require authentication with your client ID and client secret, using _one_ of the following
 * methods:
 *
 * - **Basic access authentication** (Recommended): For [basic access
 * authentication](https://en.wikipedia.org/wiki/Basic_access_authentication), the `{credentials}`
 * string must be a Base64 encoded value of `{client id}:{client secret}`.
 * - **Body parameters**:
 * Provide your integration's credentials using the `client_id` and `client_secret` body
 * parameters.
 *
 * This endpoint can't be called from a user's web-browser client because it uses client
 * authentication with client secrets. Requests must come from your integration's backend, otherwise
 * they'll be blocked by Canva's [Cross-Origin Resource Sharing
 * (CORS)](https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS) policy.
 */
class RevokeAccessToken extends Request implements HasBody
{
    use HasFormBody;

    protected Method $method = Method::POST;


    public function resolveEndpoint(): string
    {
        return "/v1/oauth/revoke";
    }


    public function __construct(
        protected string $accessToken,
        protected OAuthConfig $oauthConfig
    ) {
        //
    }


    /**
     * Get the headers for the request.
     *
     * @return array<string, string>
     */
    protected function defaultHeaders(): array
    {
        $credentials = base64_encode(
            $this->oauthConfig->getClientId() . ':' . $this->oauthConfig->getClientSecret()
        );

        return [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . $credentials,
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
            'token' => $this->accessToken,
            'client_id' => $this->oauthConfig->getClientId(),
            'client_secret' => $this->oauthConfig->getClientSecret(),
        ];
    }
}
