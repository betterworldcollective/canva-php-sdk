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
class UserProfile extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return "/v1/users/me/profile";
    }
}
