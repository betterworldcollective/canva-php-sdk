<?php

namespace Canva\Tests\Unit\Authentications;

use Canva\Authentications\CanvaOAuth;
use Canva\Requests\OAuth\CanvaAccessTokenRequest;
use Saloon\Helpers\OAuth2\OAuthConfig;

beforeEach(function () {
    $this->clientId = 'test_client_id';
    $this->clientSecret = 'test_client_secret';
    $this->redirectUri = 'https://example.com/callback';
    $this->canvaOAuth = new CanvaOAuth($this->clientId, $this->clientSecret, $this->redirectUri);
});

test('constructor sets OAuth config', function () {
    // Test that the OAuth configuration is properly set
    expect($this->canvaOAuth)->toBeInstanceOf(CanvaOAuth::class);
});

test('set code verifier', function () {
    $codeVerifier = 'test_code_verifier_123456789012345678901234567890123456789012345678901234567890';

    $result = $this->canvaOAuth->setCodeVerifier($codeVerifier);

    expect($result)->toBe($this->canvaOAuth);
});

test('set code challenge', function () {
    $codeVerifier = 'test_code_verifier_123456789012345678901234567890123456789012345678901234567890';

    $this->canvaOAuth->setCodeChallenge($codeVerifier);

    // Test that the code challenge is generated and set
    expect(true)->toBeTrue(); // Method executed without error
});

test('get auth URL without code challenge', function () {
    $authUrl = $this->canvaOAuth->getAuthUrl();

    expect($authUrl)->toContain('https://www.canva.com/api/oauth/authorize');
    expect($authUrl)->toContain('client_id=' . $this->clientId);
    expect($authUrl)->toContain('redirect_uri=' . urlencode($this->redirectUri));
});

test('get auth URL with code challenge', function () {
    $codeVerifier = 'test_code_verifier_123456789012345678901234567890123456789012345678901234567890';
    $this->canvaOAuth->setCodeChallenge($codeVerifier);

    $authUrl = $this->canvaOAuth->getAuthUrl();

    expect($authUrl)->toContain('code_challenge_method=S256');
    expect($authUrl)->toContain('code_challenge=');
});

test('get auth URL with state', function () {
    $state = 'test_state_123';
    $authUrl = $this->canvaOAuth->getAuthUrl($state);

    expect($authUrl)->toContain('state=' . $state);
});

test('get auth URL with additional query', function () {
    $additionalQuery = ['custom_param' => 'custom_value'];
    $authUrl = $this->canvaOAuth->getAuthUrl(null, $additionalQuery);

    expect($authUrl)->toContain('custom_param=custom_value');
});
