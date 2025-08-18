<?php

namespace Canva\Data\Exports;

use Canva\Data\BaseData;

/**
 * @phpstan-import-type ErrorDataResponse from Error
 *
 * @phpstan-type DesignExportJobDataResponse array{
 *     id: string,
 *     status: string,
 *     urls?: array<int, string>|null, // If the export has multiple pages, this will be an array of URLs.
 *     error?: ErrorDataResponse
 * }
 */
class DesignExportJob extends BaseData
{
    /**
     * @param ?array<int, string> $urls
     */
    public function __construct(
        public string $id,
        public string $status,
        public ?array $urls = null,
        public ?Error $error = null
   ) {
        //
    }

    /**
     * @param DesignExportJobDataResponse $data
     */
    public static function from(array $data): self
    {
        return new self(
            id: $data['id'],
            status: $data['status'],
            urls: $data['urls'] ?? null,
            error: self::convertToData($data, 'error', Error::class),
        );
    }
}
