<?php

namespace Canva\Data\App;

use Canva\Data\BaseData;

/**
 * @phpstan-type EdDsaJwkData array{
 *     kid: string,
 *     kty: string,
 *     crv: string,
 *     x: string
 * }
 */
class EdDsaJwk extends BaseData
{
    public function __construct(
        public string $kid,
        public string $kty,
        public string $crv,
        public string $x,
    ) {
    }

    /**
     * @param EdDsaJwkData $data
     */
    public static function from(array $data): self
    {
        return new self(
            kid: $data['kid'],
            kty: $data['kty'],
            crv: $data['crv'],
            x: $data['x'],
        );
    }
}
