<?php

namespace Canva\Tests\Unit\Requests\User;

use Canva\Requests\User\UserProfile;
use Saloon\Enums\Method;

beforeEach(function () {
    $this->request = new UserProfile();
});

test('constructor sets properties', function () {
    expect($this->request)->toBeInstanceOf(UserProfile::class);
});

test('resolve endpoint', function () {
    $endpoint = $this->request->resolveEndpoint();

    expect($endpoint)->toBe('/v1/users/me/profile');
});

test('method is GET', function () {
    $method = $this->request->getMethod();

    expect($method)->toBe(Method::GET);
});

test('endpoint contains correct path', function () {
    $endpoint = $this->request->resolveEndpoint();

    expect($endpoint)->toBe('/v1/users/me/profile');
});
