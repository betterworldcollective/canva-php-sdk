<?php

namespace Canva\Resources;

use Canva\Requests\Design\CreateDesign;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class DesignResource extends BaseResource
{
    /**
     * @param array<string, mixed> $properties
     * @return Response
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function create(array $properties): Response
    {
        return $this->connector->send(new CreateDesign($properties));
    }
}
