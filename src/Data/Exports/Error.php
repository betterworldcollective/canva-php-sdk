<?php

namespace Canva\Data\Exports;

use Canva\Data\BaseData;

/**
 * @phpstan-type ErrorDataResponse array{
 *     code: string,
 *     message: string
 * }
 */
class Error extends BaseData
{
    public function __construct(
        public string $code,
        public string $message
    ) {
        //
    }

    /**
     * Create an Error instance from an associative array.
     *
     * @param ErrorDataResponse $data
     */
    public static function from(array $data): self
    {
        return new self(
            code: $data['code'],
            message: $data['message']
        );
    }
}
