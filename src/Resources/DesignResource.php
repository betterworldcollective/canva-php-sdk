<?php

namespace Canva\Resources;

use Canva\Data\Designs\Design;
use Canva\Requests\Design\CreateDesign;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class DesignResource extends BaseResource
{
    /**
     * @param array<string, mixed> $properties
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function create(array $properties): Design
    {
        $response = $this->connector->send(new CreateDesign($properties));

        return $response->dto();
    }
}
