<?php

namespace Canva\Tests\Unit\Requests\OAuth;

use Canva\Requests\OAuth\RevokeAccessTokenRequest;
use Saloon\Enums\Method;
use Saloon\Helpers\OAuth2\OAuthConfig;

beforeEach(function () {
    $this->accessToken = 'test_access_token_123';

    $this->oauthConfig = OAuthConfig::make()
        ->setClientId('test_client_id')
        ->setClientSecret('test_client_secret');

    $this->request = new RevokeAccessTokenRequest($this->accessToken, $this->oauthConfig);
});

test('resolve endpoint', function () {
    $endpoint = $this->request->resolveEndpoint();

    expect($endpoint)->toBe('/v1/oauth/revoke');
});

test('method is POST', function () {
    // Access the protected property using reflection
    $reflection = new \ReflectionClass($this->request);
    $methodProperty = $reflection->getProperty('method');
    $methodProperty->setAccessible(true);

    $method = $methodProperty->getValue($this->request);

    expect($method)->toBe(Method::POST);
});
