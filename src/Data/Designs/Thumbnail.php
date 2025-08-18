<?php

namespace Canva\Data\Designs;

use Canva\Data\BaseData;

/**
 * @phpstan-type ThumbnailDataResponse array{
 *     width: int,
 *     height: int,
 *     url: string
 * }
 */
class Thumbnail extends BaseData
{
    public function __construct(
        public int $width,
        public int $height,
        public string $url
    ) {
        //
    }

    /**
     * Create a Thumbnail instance from an associative array.
     *
     * @param ThumbnailDataResponse $data
     */
    public static function from(array $data): self
    {
        return new self(
            width: $data['width'],
            height: $data['height'],
            url: $data['url']
        );
    }
}
