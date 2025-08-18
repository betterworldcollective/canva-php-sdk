<?php

namespace Canva\Resources;

use Canva\Requests\User\UserProfile;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class UserResource extends BaseResource
{
    /**
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function profile(): Response
    {
        return $this->connector->send(new UserProfile());
    }
}
