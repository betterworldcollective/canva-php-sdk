<?php

namespace Canva\Resources;

use Canva\Requests\Design\CreateDesign;
use Canva\Requests\Design\GetDesign;
use Saloon\Http\BaseResource;

class DesignResource extends BaseResource
{
    /**
     * @param array<string, mixed> $properties
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function create(array $properties): mixed
    {
        $response = $this->connector->send(new CreateDesign($properties));

        return $response->dto();
    }

    public function get(string $designId): mixed
    {
        $response = $this->connector->send(new GetDesign($designId));

        return $response->dto();
    }
}
