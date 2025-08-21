<?php

namespace Canva\Requests\Export;

use Canva\Data\Exports\DesignExportJob;
use JsonException;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

/**
 * GetDesignExportJob
 *
 * Gets the result of a design export job that was created using the [Create design export job
 * API](https://www.canva.dev/docs/connect/api-reference/exports/create-design-export-job/).
 *
 * If the
 * job is successful, the response includes an array
 * of download URLs. Depending on the design type and
 * export format, there is a download URL for each page in the design. The download URLs are only valid
 * for 24 hours.
 *
 * You might need to make multiple requests to this endpoint until you get a `success`
 * or `failed` status. For more information on the workflow for using asynchronous jobs, see [API
 * requests and
 * responses](https://www.canva.dev/docs/connect/api-requests-responses/#asynchronous-job-endpoints).
 * @phpstan-import-type ErrorDataResponse from \Canva\Data\Exports\Error
 *
 * @phpstan-type DesignExportJobDataResponse array{
 *      id: string,
 *      status: string,
 *      urls?: array<int, string>|null, // If the export has multiple pages, this will be an array of URLs.
 *      error?: ErrorDataResponse
 *  }
 */
class GetDesignExportJob extends Request
{
    protected Method $method = Method::GET;


    public function resolveEndpoint(): string
    {
        return "/v1/exports/{$this->exportId}";
    }


    /**
     * @param string $exportId The export job ID.
     */
    public function __construct(
        protected string $exportId,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function createDtoFromResponse(Response $response): DesignExportJob
    {
        $responseBody = $response->json();

        /** @var DesignExportJobDataResponse $job */
        $job = $responseBody['job'];

        return DesignExportJob::from($job);
    }
}
