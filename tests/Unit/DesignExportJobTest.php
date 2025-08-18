<?php

use Canva\Canva;
use Canva\Enums\ExportFormatType;
use Saloon\Http\Response;

$client = Canva::oauth('client-id', 'client-secret', 'redirect-uri')->authenticateWithToken('access-token');


test('can create a design export job', function () use ($client) {
    $exportJob = $client->designExportJob()->create([
        'design_id' => 'DAGwYwEDfW4',
        'format' => [
            'type' => ExportFormatType::JPG,
            'quality' => 80,
        ]
    ]);

    expect($exportJob)->toBeInstanceOf(Response::class);

    $responseBody = $exportJob->json();

    expect($responseBody)->toHaveKey('job')
        ->and($responseBody['job'])->toHaveKey('id')
        ->and($responseBody['job'])->toHaveKey('status');
});

test('can get a design export job', function () use ($client) {
    $exportJob = $client->designExportJob()->get("be67f84b-fe7d-4993-bf98-6325d436e810");

    expect($exportJob)->toBeInstanceOf(Response::class);

    $responseBody = $exportJob->json();

    expect($responseBody)->toHaveKey('job')
        ->and($responseBody['job'])->toHaveKey('id')
        ->and($responseBody['job'])->toHaveKey('status')
        ->and($responseBody['job'])->toHaveKey('urls')
        ->and($responseBody['job']['urls'])->toBeArray();
});
