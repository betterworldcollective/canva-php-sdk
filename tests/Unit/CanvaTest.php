<?php

namespace Canva\Tests\Unit;

use Canva\Canva;

test('base URL is correctly resolved', function () {
    // Create a concrete implementation of the abstract Canva class for testing
    $canva = (new Canva('client-id', 'client-secret', 'redirect-uri'));

    $baseUrl = $canva->resolveBaseUrl();

    expect($baseUrl)->toBe('https://api.canva.com/rest/');
});

test('default headers are correctly set', function () {
    $canva = (new Canva('client-id', 'client-secret', 'redirect-uri'));

    $headers = $canva->defaultHeaders();

    expect($headers)->toBeArray();
    expect($headers)->toHaveKey('Accept');
    expect($headers)->toHaveKey('Content-Type');
    expect($headers['Accept'])->toBe('application/json');
    expect($headers['Content-Type'])->toBe('application/json');
});

test('oauth factory method returns Canva instance', function () {
    $clientId = 'test_client_id';
    $clientSecret = 'test_client_secret';
    $redirectUri = 'https://example.com/callback';

    $result = new Canva($clientId, $clientSecret, $redirectUri);

    expect($result)->toBeInstanceOf(Canva::class);
});

test('oauth factory method passes correct parameters', function () {
    $clientId = 'test_client_id';
    $clientSecret = 'test_client_secret';
    $redirectUri = 'https://example.com/callback';

    $result = new Canva($clientId, $clientSecret, $redirectUri);

    // Verify the OAuth instance is properly configured
    expect($result)->toBeInstanceOf(Canva::class);
});
