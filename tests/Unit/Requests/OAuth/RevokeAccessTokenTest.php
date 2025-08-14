<?php

namespace Canva\Tests\Unit\Requests\OAuth;

use Canva\Requests\OAuth\RevokeAccessToken;
use Saloon\Enums\Method;
use Saloon\Helpers\OAuth2\OAuthConfig;

beforeEach(function () {
    $this->accessToken = 'test_access_token_123';

    $this->oauthConfig = OAuthConfig::make()
        ->setClientId('test_client_id')
        ->setClientSecret('test_client_secret');

    $this->request = new RevokeAccessToken($this->accessToken, $this->oauthConfig);
});

test('resolve endpoint', function () {
    $endpoint = $this->request->resolveEndpoint();

    expect($endpoint)->toBe('/v1/oauth/revoke');
});

test('method is POST', function () {
    $method = $this->request->getMethod();

    expect($method)->toBe(Method::POST);
});
