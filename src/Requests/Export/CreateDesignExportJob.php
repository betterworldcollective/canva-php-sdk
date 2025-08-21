<?php

namespace Canva\Requests\Export;

use Canva\Data\Exports\DesignExportJob;
use JsonException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

/**
 * CreateDesignExportJob
 *
 * Starts a new [asynchronous
 * job](https://www.canva.dev/docs/connect/api-requests-responses/#asynchronous-job-endpoints) to
 * export a file from Canva. Once the exported file is generated, you can download
 * it using the URL(s)
 * provided. The download URLs are only valid for 24 hours.
 *
 * The request requires the design ID and the
 * exported file format type.
 *
 * Supported file formats (and export file type values): PDF (`pdf`), JPG
 * (`jpg`), PNG (`png`), GIF (`gif`), Microsoft PowerPoint (`pptx`), and MP4 (`mp4`).
 *
 * <Note>
 *
 * For more
 * information on the workflow for using asynchronous jobs, see [API requests and
 * responses](https://www.canva.dev/docs/connect/api-requests-responses/#asynchronous-job-endpoints).
 * You can check the status and get the results of export jobs created with this API using the [Get
 * design export job
 * API](https://www.canva.dev/docs/connect/api-reference/exports/get-design-export-job/).
 *
 * </Note>
 *
 * @phpstan-type ExportFormat array{
 *     design_id: string,
 *     format: array{
 *         type: string,
 *         export_quality?: string,
 *         size?: string,
 *         pages?: array<int>
 *     }
 * }
 *
 * @phpstan-import-type ErrorDataResponse from \Canva\Data\Exports\Error
 *
 * @phpstan-type DesignExportJobDataResponse array{
 *      id: string,
 *      status: string,
 *      urls?: array<int, string>|null, // If the export has multiple pages, this will be an array of URLs.
 *      error?: ErrorDataResponse
 * }
 */
class CreateDesignExportJob extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;


    public function resolveEndpoint(): string
    {
        return "/v1/exports";
    }

    /**
     * CreateDesignExportJob constructor.
     *
     * @param ExportFormat $properties
     */
    public function __construct(protected array $properties)
    {
        // Ensure that required properties are set
        if (empty($this->properties['design_id'])) {
            throw new \InvalidArgumentException('The design_id is required.');
        }

        if (empty($this->properties['format'])) {
            throw new \InvalidArgumentException('The format is required.');
        }

        // Type-safe access with proper validation
        $format = $this->properties['format'];
        if (empty($format['type'])) {
            throw new \InvalidArgumentException('The format type is required.');
        }
    }

    /**
     * Get the default body for the request.
     *
     * @return ExportFormat
     */
    public function defaultBody(): array
    {
        return $this->properties;
    }

    /**
     * @throws JsonException
     */
    public function createDtoFromResponse(Response $response): DesignExportJob
    {
        $data = $response->json();
        /** @var DesignExportJobDataResponse $job */
        $job = $data['job'];

        return DesignExportJob::from($job);
    }
}
