<?php

namespace Canva\Resources;

use Canva\Requests\Export\CreateDesignExportJob;
use Canva\Requests\Export\GetDesignExportJob;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class DesignExportJobResource extends BaseResource
{
    /**
     * Creates a new design export job.
     *
     * @param array<string, mixed> $properties The properties for the export job.
     * @return Response
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
     */
    public function create(array $properties): Response
    {
        return $this->connector->send(new CreateDesignExportJob($properties));
    }


    /**
     * @param string $exportId The export job ID.
     * @throws \Saloon\Exceptions\Request\FatalRequestException
     * @throws \Saloon\Exceptions\Request\RequestException
 */
    public function get(string $exportId): Response
    {
        return $this->connector->send(new GetDesignExportJob($exportId));
    }
}
