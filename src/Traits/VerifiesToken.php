<?php

namespace Canva\Traits;

use Canva\Data\App\EdDsaJwk;
use Canva\Data\App\Key;
use Canva\Data\BaseData;
use Exception;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Collection;

/**
 * Trait to verify JWT tokens using a set of JWK keys.
 *
 * @phpstan-import-type EdDsaJwkData from EdDsaJwk
 */
trait VerifiesToken
{
    /**
     * @throws Exception
     * @param string $correlationJwt The JWT token to verify.
     * @param (EdDsaJwkData|object)[] $keys
     * @param string $appId The expected app / client ID to verify against the token's payload.
     */
    public function verifyToken(string $correlationJwt, array $keys, string $appId): \stdClass
    {
        // Parse the JWK set and decode the JWT
        $parsedKeys = JWK::parseKeySet($this->getJwkSet($keys));
        $decoded = JWT::decode($correlationJwt, $parsedKeys);

        // Optionally verify the app_id if it's in the payload
        if (property_exists($decoded, 'aud') && $decoded->aud !== $appId) {
            throw new \Exception('App ID mismatch in JWT payload');
        }

        return $decoded;
    }

    /**
     * Get the JWK set from the keys.
     *
     * @param (EdDsaJwkData|object)[] $keys
     * @return array{keys: EdDsaJwkData[]}
     */
    private function getJwkSet(array $keys): array
    {
        /** @var array<string, EdDsaJwkData> $jwkSet */
        $jwkSet = [];

        foreach ($keys as $key) {
            $keyData = is_object($key) ? get_object_vars($key) : $key;

            if ($keyData['crv'] === 'Ed25519') {
                $keyData['alg'] = 'EdDSA';
            }

            if (isset($keyData['kid'])) {
                $jwkSet[$keyData['kid']] = $keyData;
            }
        }

        /** @var EdDsaJwkData[] $keysArray */
        $keysArray = array_values($jwkSet);
        return ['keys' => $keysArray];
    }
}
