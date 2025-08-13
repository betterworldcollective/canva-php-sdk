<?php

namespace Canva\Tests\Unit\Requests\OAuth;

use Canva\Requests\OAuth\CanvaAccessTokenRequest;
use Saloon\Enums\Method;
use Saloon\Helpers\OAuth2\OAuthConfig;

beforeEach(function () {
    $this->code = 'test_auth_code';
    $this->codeVerifier = 'test_code_verifier_123456789012345678901234567890123456789012345678901234567890';

    $this->oauthConfig = OAuthConfig::make()
        ->setClientId('test_client_id')
        ->setClientSecret('test_client_secret')
        ->setRedirectUri('https://example.com/callback');

    $this->request = new CanvaAccessTokenRequest($this->code, $this->oauthConfig, $this->codeVerifier);
});

test('resolve endpoint', function () {
    $endpoint = $this->request->resolveEndpoint();

    expect($endpoint)->toBe('https://api.canva.com/rest/v1/oauth/token');
});

test('method is POST', function () {
    // Access the protected property using reflection
    $reflection = new \ReflectionClass($this->request);
    $methodProperty = $reflection->getProperty('method');
    $methodProperty->setAccessible(true);

    $method = $methodProperty->getValue($this->request);

    expect($method)->toBe(Method::POST);
});
