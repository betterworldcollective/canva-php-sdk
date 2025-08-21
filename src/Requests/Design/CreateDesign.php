<?php

namespace Canva\Requests\Design;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
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
 */
class CreateDesign extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;


    public function resolveEndpoint(): string
    {
        return "/v1/designs";
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
}
