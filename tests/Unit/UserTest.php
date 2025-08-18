<?php

use Canva\Canva;
use Saloon\Http\Response;

$client = Canva::oauth('client-id', 'client-secret', 'redirect-uri')->authenticateWithToken('access-token');

test('can create a design', function () use ($client) {
    $design = $client->user()->profile();

    expect($design)->toBeInstanceOf(Response::class);

    $responseBody = $design->json();

    expect($responseBody)->toHaveKey('profile')
        ->and($responseBody['profile'])->toHaveKey('display_name')
        ->and($responseBody['profile']['display_name'])->toBe('John Doe');
});
