<?php

namespace Canva\Data\Designs;

use Canva\Data\BaseData;
use Carbon\CarbonImmutable;

/**
 * @phpstan-import-type OwnerDataResponse from Owner
 * @phpstan-import-type UrlsDataResponse from Urls
 * @phpstan-import-type ThumbnailDataResponse from Thumbnail
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
class Design extends BaseData
{
    public function __construct(
        public string $id,
        public string $title,
        public CarbonImmutable $created_at,
        public CarbonImmutable $updated_at,
        public int $page_count,
        public ?Owner $owner = null,
        public ?Urls $urls = null,
        public ?Thumbnail $thumbnail = null,
    ) {
        //
    }

    /**
     * Create a Design instance from an associative array.
     *
     * @param DesignDataResponse $data
     */
    public static function from(array $data): self
    {
        return new self(
            id: $data['id'],
            title: $data['title'],
            created_at: CarbonImmutable::parse($data['created_at']),
            updated_at: CarbonImmutable::parse($data['updated_at']),
            page_count: $data['page_count'],
            owner: self::convertToData($data, 'owner', Owner::class),
            urls: self::convertToData($data, 'urls', Urls::class),
            thumbnail: self::convertToData($data, 'thumbnail', Thumbnail::class)
        );
    }
}
