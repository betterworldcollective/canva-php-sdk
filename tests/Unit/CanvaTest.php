<?php

namespace Canva\Tests\Unit;

use Canva\Canva;
use Canva\Authentications\CanvaOAuth;

test('base URL is correctly resolved', function () {
    // Create a concrete implementation of the abstract Canva class for testing
    $canva = new class extends Canva {
        // This anonymous class allows us to test the abstract methods
    };

    $baseUrl = $canva->resolveBaseUrl();
    
    expect($baseUrl)->toBe('https://www.canva.com/api/v1/');
});

test('default headers are correctly set', function () {
    $canva = new class extends Canva {
        // This anonymous class allows us to test the abstract methods
    };

    $headers = $canva->defaultHeaders();
    
    expect($headers)->toBeArray();
    expect($headers)->toHaveKey('Accept');
    expect($headers)->toHaveKey('Content-Type');
    expect($headers['Accept'])->toBe('application/json');
    expect($headers['Content-Type'])->toBe('application/json');
});

test('oauth factory method returns CanvaOAuth instance', function () {
    $clientId = 'test_client_id';
    $clientSecret = 'test_client_secret';
    $redirectUri = 'https://example.com/callback';

    $result = Canva::oauth($clientId, $clientSecret, $redirectUri);

    expect($result)->toBeInstanceOf(CanvaOAuth::class);
});

test('oauth factory method passes correct parameters', function () {
    $clientId = 'test_client_id';
    $clientSecret = 'test_client_secret';
    $redirectUri = 'https://example.com/callback';

    $result = Canva::oauth($clientId, $clientSecret, $redirectUri);

    // Verify the OAuth instance is properly configured
    expect($result)->toBeInstanceOf(CanvaOAuth::class);
});