<?php

namespace Canva\Requests\Export;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
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
     * @param array<string, mixed> $properties
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
    }

    /**
     * Get the default body for the request.
     *
     * @return array<string, mixed>
     */
    public function defaultBody(): array
    {
        return $this->properties;
    }
}
