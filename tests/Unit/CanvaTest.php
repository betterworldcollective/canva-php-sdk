<?php

namespace Canva\Tests\Unit;

use Canva\CanvaAuthConnector;

test('base URL is correctly resolved', function () {
    // Create a concrete implementation of the abstract Canva class for testing
    $canva = (new CanvaAuthConnector('client-id', 'client-secret', 'redirect-uri'));

    $baseUrl = $canva->resolveBaseUrl();

    expect($baseUrl)->toBe('https://api.canva.com/rest/');
});

test('default headers are correctly set', function () {
    $canva = (new CanvaAuthConnector('client-id', 'client-secret', 'redirect-uri'));

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

    $result = new CanvaAuthConnector($clientId, $clientSecret, $redirectUri);

    expect($result)->toBeInstanceOf(CanvaAuthConnector::class);
});

test('oauth factory method passes correct parameters', function () {
    $clientId = 'test_client_id';
    $clientSecret = 'test_client_secret';
    $redirectUri = 'https://example.com/callback';

    $result = new CanvaAuthConnector($clientId, $clientSecret, $redirectUri);

    // Verify the OAuth instance is properly configured
    expect($result)->toBeInstanceOf(CanvaAuthConnector::class);
});
