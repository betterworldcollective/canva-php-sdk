<?php

namespace Canva\Resources;

use Canva\Requests\App\GetKeys;
use Saloon\Http\BaseResource;

class KeyResource extends BaseResource
{
    /**
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function get(): mixed
    {
        return $this->connector->send(new GetKeys())->dto();
    }
}
