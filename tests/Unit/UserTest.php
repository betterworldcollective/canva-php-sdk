<?php

use Canva\Canva;
use Saloon\Http\Response;

$client = (new Canva('client_id', 'client_secret', 'redirect_uri'))->authenticateWithToken('access_token');

test('can create a design', function () use ($client) {
    $design = $client->user()->profile();

    expect($design)->toBeInstanceOf(Response::class);

    $responseBody = $design->json();

    expect($responseBody)->toHaveKey('profile')
        ->and($responseBody['profile'])->toHaveKey('display_name')
        ->and($responseBody['profile']['display_name'])->toBe('John Doe');
});
