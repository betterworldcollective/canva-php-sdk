<?php

namespace Canva\Tests\Unit\Requests\User;

use Canva\Requests\User\UserProfileRequest;
use Saloon\Enums\Method;

beforeEach(function () {
    $this->accessToken = 'test_access_token_123';
    $this->request = new UserProfileRequest($this->accessToken);
});

test('constructor sets properties', function () {
    expect($this->request)->toBeInstanceOf(UserProfileRequest::class);
});

test('resolve endpoint', function () {
    $endpoint = $this->request->resolveEndpoint();

    expect($endpoint)->toBe('https://api.canva.com/rest/v1/users/me/profile');
});

test('method is GET', function () {
    // Access the protected property using reflection
    $reflection = new \ReflectionClass($this->request);
    $methodProperty = $reflection->getProperty('method');
    $methodProperty->setAccessible(true);

    $method = $methodProperty->getValue($this->request);

    expect($method)->toBe(Method::GET);
});

test('endpoint is absolute URL', function () {
    $endpoint = $this->request->resolveEndpoint();

    // The endpoint should be an absolute URL
    expect($endpoint)->toStartWith('http');
    expect($endpoint)->toContain('api.canva.com');
});

test('endpoint contains correct path', function () {
    $endpoint = $this->request->resolveEndpoint();

    expect($endpoint)->toContain('/rest/v1/users/me/profile');
});
