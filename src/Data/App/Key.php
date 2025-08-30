<?php

namespace Canva\Data\App;

use Canva\Data\BaseData;

/**
 * @phpstan-import-type EdDsaJwkData from EdDsaJwk
 *
 * @phpstan-type KeyData array{
 *     keys: array<EdDsaJwkData>
 * }
 */
class Key extends BaseData
{
    /**
     * @param EdDsaJwk[] $keys
     */
    public function __construct(
        public array $keys,
    ) {
      //
    }

    /**
     * @param KeyData $data
     */
    public static function from(array $data): self
    {
        return new self(
            keys: array_map(fn($key) => EdDsaJwk::from($key), $data['keys'])
        );
    }
}
