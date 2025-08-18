<?php

use Canva\Canva;
use Canva\Data\Designs\Design;
use Canva\Enums\DesignType;
use Saloon\Http\Response;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Canva\Requests\Design\CreateDesign;
use Canva\Enums\ExportFormatType;

$client = Canva::oauth('client-id', 'client-secret', 'redirect-uri')->authenticateWithToken('access-token');

test('can create a design', function () use ($client) {
    $design = $client->design()->create([
       'design_type' => [
           'type' => DesignType::Custom,
           'width' => 800,
           'height' => 600,
       ],
        'title' => 'Another design 101!!!',
    ]);

    expect($design)->toBeInstanceOf(Response::class);

    $responseBody = $design->json();

    expect($responseBody)->toHaveKey('design')
        ->and($responseBody['design'])->toHaveKey('id')
        ->and($responseBody['design']['id'])->toBe('DAGwYwEDfW4')
        ->and($responseBody['design'])->toHaveKey('title')
        ->and($responseBody['design']['title'])->toBe('Another design 101!!!')
        ->and($responseBody['design'])->toHaveKey('owner')
        ->and($responseBody['design'])->toHaveKey('urls');
});
