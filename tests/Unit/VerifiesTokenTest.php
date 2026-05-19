<?php

namespace Canva\Tests\Unit;

use Canva\Canva;
use Canva\Data\App\EdDsaJwk;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

test('decodeJwt method exists and can be called', function () {
    expect(method_exists(Canva::class, 'decodeJwt'))->toBeTrue()
        ->and(method_exists(Canva::class, 'getJwkSet'))->toBeTrue();
});

test('decodeJwt method signature is correct', function () {
    $reflection = new \ReflectionClass(Canva::class);
    $method = $reflection->getMethod('decodeJwt');

    expect($method->isPublic())->toBeTrue()
        ->and($method->isStatic())->toBeTrue()
        ->and($method->getNumberOfParameters())->toBe(2);

    $params = $method->getParameters();
    expect($params[0]->getName())->toBe('correlationJwt')
        ->and($params[1]->getName())->toBe('keys');
});

test('getJwkSet correctly formats keys for JWT verification', function () {
    $keys = [
        [
            'kid' => 'test-key-1',
            'kty' => 'OKP',
            'crv' => 'Ed25519',
            'x' => 'test-x-coordinate'
        ],
        [
            'kid' => 'test-key-2',
            'kty' => 'OKP',
            'crv' => 'Ed25519',
            'x' => 'another-x-coordinate'
        ]
    ];

    // Use reflection to test the private method
    $reflection = new \ReflectionClass(Canva::class);
    $method = $reflection->getMethod('getJwkSet');
    $method->setAccessible(true);

    $result = $method->invoke(null, $keys);

    expect($result)->toBeArray()
        ->and($result)->toHaveKey('keys')
        ->and($result['keys'])->toBeArray()
        ->and($result['keys'])->toHaveCount(2);

    // Check that Ed25519 keys get the 'alg' field added
    foreach ($result['keys'] as $key) {
        expect($key)->toHaveKey('kid')
            ->and($key)->toHaveKey('kty')
            ->and($key)->toHaveKey('crv')
            ->and($key)->toHaveKey('x');

        if ($key['crv'] === 'Ed25519') {
            expect($key)->toHaveKey('alg')
                ->and($key['alg'])->toBe('EdDSA');
        }
    }
});

test('getJwkSet handles keys without kid gracefully', function () {
    $keys = [
        [
            'kty' => 'OKP',
            'crv' => 'Ed25519',
            'x' => 'test-x-coordinate'
            // Missing 'kid'
        ]
    ];

    $reflection = new \ReflectionClass(Canva::class);
    $method = $reflection->getMethod('getJwkSet');
    $method->setAccessible(true);

    $result = $method->invoke(null, $keys);

    expect($result)->toBeArray()
        ->and($result)->toHaveKey('keys')
        ->and($result['keys'])->toBeArray()
        ->and($result['keys'])->toHaveCount(0);
    // Key without kid should be excluded
});

test('getJwkSet processes EdDsaJwk objects correctly', function () {
    $edDsaJwk = new EdDsaJwk(
        kid: 'test-key-1',
        kty: 'OKP',
        crv: 'Ed25519',
        x: 'test-x-coordinate'
    );

    $keys = [$edDsaJwk];

    $reflection = new \ReflectionClass(Canva::class);
    $method = $reflection->getMethod('getJwkSet');
    $method->setAccessible(true);

    $result = $method->invoke(null, $keys);

    expect($result)->toBeArray()
        ->and($result)->toHaveKey('keys')
        ->and($result['keys'])->toHaveCount(1);

    $key = $result['keys'][0];
    expect($key['kid'])->toBe('test-key-1')
        ->and($key['kty'])->toBe('OKP')
        ->and($key['crv'])->toBe('Ed25519')
        ->and($key['x'])->toBe('test-x-coordinate')
        ->and($key['alg'])->toBe('EdDSA');
});
