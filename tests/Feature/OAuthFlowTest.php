<?php

namespace Canva\Tests\Feature;

use Canva\Authentications\CanvaOAuth;
use Canva\Requests\OAuth\CanvaAccessToken;
use Canva\Requests\OAuth\RevokeAccessToken;
use Canva\Requests\User\UserProfile;
use Saloon\Helpers\OAuth2\OAuthConfig;

beforeEach(function () {
    $this->clientId = 'test_client_id';
    $this->clientSecret = 'test_client_secret';
    $this->redirectUri = 'https://example.com/callback';
});

test('complete OAuth flow', function () {
    // Step 1: Create OAuth instance
    $canvaOAuth = new CanvaOAuth($this->clientId, $this->clientSecret, $this->redirectUri);

    expect($canvaOAuth)->toBeInstanceOf(CanvaOAuth::class);

    // Step 2: Set up PKCE code verifier
    $codeVerifier = 'test_code_verifier_123456789012345678901234567890123456789012345678901234567890';
    $canvaOAuth->setCodeVerifier($codeVerifier);

    // Step 3: Generate authorization URL
    $authUrl = $canvaOAuth->getAuthUrl('test_state_123');

    expect($authUrl)->toContain('https://www.canva.com/api/oauth/authorize');
    expect($authUrl)->toContain('client_id=' . $this->clientId);
    expect($authUrl)->toContain('redirect_uri=' . urlencode($this->redirectUri));
    expect($authUrl)->toContain('state=test_state_123');
    expect($authUrl)->toContain('code_challenge_method=S256');
    expect($authUrl)->toContain('code_challenge=');

    // Step 4: Simulate receiving authorization code
    $authCode = 'test_auth_code_123';

    // Step 5: Create access token request
    $oauthConfig = OAuthConfig::make()
        ->setClientId($this->clientId)
        ->setClientSecret($this->clientSecret)
        ->setRedirectUri($this->redirectUri);

    $accessTokenRequest = new CanvaAccessToken($authCode, $oauthConfig, $codeVerifier);

    expect($accessTokenRequest)->toBeInstanceOf(CanvaAccessToken::class);
    expect($accessTokenRequest->resolveEndpoint())->toBe('https://api.canva.com/rest/v1/oauth/token');

    // Step 6: Simulate having an access token and create user profile request
    $userProfileRequest = new UserProfile();

    expect($userProfileRequest)->toBeInstanceOf(UserProfile::class);
    expect($userProfileRequest->resolveEndpoint())->toBe('/v1/users/me/profile');
});

test('PKCE flow', function () {
    $canvaOAuth = new CanvaOAuth($this->clientId, $this->clientSecret, $this->redirectUri);

    // Test PKCE code verifier and challenge generation
    $codeVerifier = 'test_code_verifier_123456789012345678901234567890123456789012345678901234567890';
    $canvaOAuth->setCodeChallenge($codeVerifier);

    $authUrl = $canvaOAuth->getAuthUrl();

    // Verify PKCE parameters are included
    expect($authUrl)->toContain('code_challenge_method=S256');
    expect($authUrl)->toContain('code_challenge=');

    // The code challenge should be different from the code verifier
    expect($authUrl)->not->toContain($codeVerifier);
});
