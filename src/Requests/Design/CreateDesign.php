<?php

namespace Canva\Requests\Design;

use Canva\Data\Designs\Design;
use Canva\Exceptions\CanvaApiException;
use Canva\Traits\DecodesResponses;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

/**
 * CreateDesign
 *
 * Creates a new Canva design. To create a new design, you can either:
 *
 * - Use a preset design type.
 * -
 * Set height and width dimensions for a custom design.
 *
 * Additionally, you can also provide the
 * `asset_id` of an asset in the user's
 * [projects](https://www.canva.com/help/find-designs-and-folders/) to add to the new design.
 * Currently, this only supports image assets. To list the assets in a folder in the user's projects,
 * use the [List folder items
 * API](https://www.canva.dev/docs/connect/api-reference/folders/list-folder-items/).
 *
 * NOTE: Blank
 * designs created with this API are automatically deleted if they're not edited within 7 days. These
 * blank designs bypass the user's Canva trash and are permanently deleted.
 *
 * @phpstan-import-type OwnerDataResponse from \Canva\Data\Designs\Owner
 * @phpstan-import-type UrlsDataResponse from \Canva\Data\Designs\Urls
 * @phpstan-import-type ThumbnailDataResponse from \Canva\Data\Designs\Thumbnail
 *
 * @phpstan-type DesignDataResponse array{
 *     id: string,
 *     title: string,
 *     owner: OwnerDataResponse,
 *     urls: UrlsDataResponse,
 *     thumbnail?: ThumbnailDataResponse,
 *     created_at: string,
 *     updated_at: string,
 *     page_count: int
 * }
 */
class CreateDesign extends Request implements HasBody
{
    use DecodesResponses;
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/v1/designs';
    }

    /**
     * CreateDesign constructor.
     *
     * @phpstan-param array<string, mixed> $properties
     * @param array $properties
     *   An associative array of properties to set for the design.
     *   Example:
     *   [
     *       'type' => 'presentation',
     *       'width' => 1920,
     *       'height' => 1080,
     *       'asset_id' => '1234567890abcdef',
     *   ]
     */
    public function __construct(protected array $properties)
    {
        //
    }

    /**
     * Get the default body for the request.
     *
     * @phpstan-return array<string, mixed>
     * The properties to be sent in the request body.
     */
    public function defaultBody(): array
    {
        return $this->properties;
    }

    /**
     * @throws CanvaApiException
     */
    public function createDtoFromResponse(Response $response): Design
    {
        /** @var DesignDataResponse $design */
        $design = $this->payload($response, 'design');

        return Design::from($design);
    }
}
