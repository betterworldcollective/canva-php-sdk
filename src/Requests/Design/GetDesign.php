<?php

namespace Canva\Requests\Design;

use Canva\Data\Designs\Design;
use Canva\Exceptions\CanvaApiException;
use Canva\Traits\DecodesResponses;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

/**
 * GetDesign
 *
 * Gets the metadata for a design. This includes owner information, URLs for editing and viewing, and
 * thumbnail information.
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
class GetDesign extends Request
{
    use DecodesResponses;

    protected Method $method = Method::GET;

    /**
     * @param  string  $designId  The design ID.
     */
    public function __construct(
        protected string $designId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/v1/designs/{$this->designId}";
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
