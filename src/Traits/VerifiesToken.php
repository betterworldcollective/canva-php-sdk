<?php

namespace Canva\Traits;

use Canva\Data\App\EdDsaJwk;
use Canva\Data\App\Key;
use Canva\Data\BaseData;
use Exception;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Collection;
use stdClass;

/**
 * Trait to verify JWT tokens using a set of JWK keys.
 *
 * @phpstan-import-type EdDsaJwkData from EdDsaJwk
 */
trait VerifiesToken
{
    /**
     * @param string $correlationJwt The JWT token to verify.
     * @param (EdDsaJwkData|object)[] $keys
     * @return stdClass
     * @throws Exception
     */
    public static function decodeJwt(string $correlationJwt, array $keys): \stdClass
    {
        // Parse the JWK set and decode the JWT
        $parsedKeys = JWK::parseKeySet(self::getJwkSet($keys));

        return JWT::decode($correlationJwt, $parsedKeys);
    }

    /**
     * Get the JWK set from the keys.
     *
     * @param (EdDsaJwkData|object)[] $keys
     * @return array{keys: EdDsaJwkData[]}
     */
    private static function getJwkSet(array $keys): array
    {
        /** @var array<string, EdDsaJwkData> $jwkSet */
        $jwkSet = [];

        foreach ($keys as $key) {
            $keyData = is_object($key) ? get_object_vars($key) : $key;

            if ($keyData['crv'] === 'Ed25519') {
                $keyData['alg'] = 'EdDSA';
            }

            if (isset($keyData['kid']) && is_string($keyData['kid'])) {
                $jwkSet[$keyData['kid']] = $keyData;
            }
        }

        /** @var EdDsaJwkData[] $keysArray */
        $keysArray = array_values($jwkSet);
        return ['keys' => $keysArray];
    }
}
