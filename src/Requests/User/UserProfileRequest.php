<?php

namespace Canva\Requests\User;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * UserProfileRequest
 *
 * Currently, this returns the display name of the user account associated with the provided access
 * token. More user information is expected to be included in the future.
 */
class UserProfileRequest extends Request
{
    protected Method $method = Method::GET;


    public function resolveEndpoint(): string
    {
        return "https://api.canva.com/rest/v1/users/me/profile";
    }


    public function __construct(
        protected string $accessToken
    ) {
        //
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken,
        ];
    }
}
