<?php

namespace Canva\Data\Designs;

use Canva\Data\BaseData;

/**
 * @phpstan-type UrlsDataResponse array{
 *     view_url: string,
 *     edit_url: string
 * }
 */
class Urls extends BaseData
{
    public function __construct(
        public string $view_url,
        public string $edit_url
    ) {
        //
    }

    /**
     * Create a Urls instance from an associative array.
     *
     * @param UrlsDataResponse $data
     */
    public static function from(array $data): self
    {
        return new self(
            view_url: $data['view_url'],
            edit_url: $data['edit_url']
        );
    }
}
