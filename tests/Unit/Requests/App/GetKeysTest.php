<?php

namespace Canva\Tests\Unit\Requests\App;

use Canva\Canva;
use Canva\Requests\App\GetKeys;
use Canva\Data\App\Key;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->request = new GetKeys();
});

test('constructor creates instance', function () {
    expect($this->request)->toBeInstanceOf(GetKeys::class);
});

test('resolve endpoint', function () {
    $endpoint = $this->request->resolveEndpoint();

    expect($endpoint)->toBe('v1/connect/keys');
});

test('method is GET', function () {
    $method = $this->request->getMethod();

    expect($method)->toBe(Method::GET);
});

test('can create dto from response', function () {
    $mockClient = new MockClient([
        GetKeys::class => MockResponse::make([
            'keys' => [
                [
                    'kid' => 'test-key-1',
                    'kty' => 'EC',
                    'crv' => 'P-256',
                    'x' => 'test-x-coordinate',
                    'y' => 'test-y-coordinate'
                ]
            ]
        ], 200)
    ]);

    $connector = new Canva('client-id', 'client-secret', 'redirect-uri');
    $connector->withMockClient($mockClient);

    $response = $connector->send(new GetKeys());
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Key::class);
    expect($dto->keys)->toHaveCount(1);
    expect($dto->keys[0]->kid)->toBe('test-key-1');
    expect($dto->keys[0]->kty)->toBe('EC');
    expect($dto->keys[0]->crv)->toBe('P-256');

    $mockClient->assertSent(GetKeys::class);
    $mockClient->assertSentCount(1);
});
