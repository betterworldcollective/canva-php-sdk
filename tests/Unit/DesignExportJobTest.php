<?php

use Canva\CanvaApiConnector;
use Canva\Data\Exports\DesignExportJob;
use Canva\Enums\ExportFormatType;

$client = new CanvaApiConnector('access-token');


test('can create a design export job', function () use ($client) {
    /** @var DesignExportJob $exportJob */
    $exportJob = $client->designExportJob()->create([
        'design_id' => 'DAGwYwEDfW4',
        'format' => [
            'type' => ExportFormatType::JPG,
            'quality' => 80,
        ]
    ]);

    expect($exportJob)->toBeInstanceOf(DesignExportJob::class);
    expect($exportJob)->toHaveKey('id')
        ->and($exportJob->id)->toBe('be67f84b-fe7d-4993-bf98-6325d436e810')
        ->and($exportJob)->toHaveKey('status');
});

test('can get a design export job', function () use ($client) {
    /** @var DesignExportJob $exportJob */
    $exportJob = $client->designExportJob()->get("be67f84b-fe7d-4993-bf98-6325d436e810");

    expect($exportJob)->toBeInstanceOf(DesignExportJob::class);
    expect($exportJob)->toHaveKey('id')
        ->and($exportJob->id)->toBe('be67f84b-fe7d-4993-bf98-6325d436e810')
        ->and($exportJob)->toHaveKey('status');
});
