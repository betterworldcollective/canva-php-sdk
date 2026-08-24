<?php

use Canva\Canva;
use Canva\Data\Exports\DesignExportJob;
use Canva\Enums\ExportFormatType;
use Canva\Exceptions\CanvaApiException;
use Canva\Exceptions\CanvaMalformedResponseException;
use Canva\Exceptions\CanvaNotFoundException;
use Canva\Exceptions\CanvaUnauthorizedException;
use Canva\Requests\Export\CreateDesignExportJob;
use Canva\Requests\Export\GetDesignExportJob;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

$client = (new Canva('client_id', 'client_secret', 'redirect_uri'))->authenticateWithToken('access_token');

test('can create a design export job', function () use ($client) {
    /** @var DesignExportJob $exportJob */
    $exportJob = $client->designExportJob()->create([
        'design_id' => 'DAGwYwEDfW4',
        'format' => [
            'type' => ExportFormatType::JPG,
            'quality' => 80,
        ],
    ]);

    expect($exportJob)->toBeInstanceOf(DesignExportJob::class);
    expect($exportJob)->toHaveKey('id')
        ->and($exportJob->id)->toBe('be67f84b-fe7d-4993-bf98-6325d436e810')
        ->and($exportJob)->toHaveKey('status');
});

test('can get a design export job', function () use ($client) {
    /** @var DesignExportJob $exportJob */
    $exportJob = $client->designExportJob()->get('be67f84b-fe7d-4993-bf98-6325d436e810');

    expect($exportJob)->toBeInstanceOf(DesignExportJob::class);
    expect($exportJob)->toHaveKey('id')
        ->and($exportJob->id)->toBe('be67f84b-fe7d-4993-bf98-6325d436e810')
        ->and($exportJob)->toHaveKey('status');
});

test('a canva error is thrown as a typed exception rather than breaking dto hydration', function () {
    $mockClient = new MockClient([
        GetDesignExportJob::class => MockResponse::make(
            ['code' => 'not_found', 'message' => 'Export job not found.'],
            404,
        ),
    ]);

    $client = (new Canva('client_id', 'client_secret', 'redirect_uri'))
        ->authenticateWithToken('access_token')
        ->withMockClient($mockClient);

    expect(fn () => $client->designExportJob()->get('does-not-exist'))
        ->toThrow(function (CanvaNotFoundException $exception) {
            expect($exception->getStatus())->toBe(404)
                ->and($exception->getErrorCode())->toBe('not_found')
                ->and($exception->getMessage())->toBe('Export job not found.');
        });
});

test('an unauthorized response is thrown as an unauthorized exception', function () {
    $mockClient = new MockClient([
        GetDesignExportJob::class => MockResponse::make(
            ['code' => 'permission_denied', 'message' => 'Missing scope.'],
            403,
        ),
    ]);

    $client = (new Canva('client_id', 'client_secret', 'redirect_uri'))
        ->authenticateWithToken('access_token')
        ->withMockClient($mockClient);

    expect(fn () => $client->designExportJob()->get('be67f84b-fe7d-4993-bf98-6325d436e810'))
        ->toThrow(CanvaUnauthorizedException::class);
});

test('a canva outage keeps the base exception and its status', function () {
    $mockClient = new MockClient([
        CreateDesignExportJob::class => MockResponse::make('<html>Bad Gateway</html>', 502),
    ]);

    $client = (new Canva('client_id', 'client_secret', 'redirect_uri'))
        ->authenticateWithToken('access_token')
        ->withMockClient($mockClient);

    expect(fn () => $client->designExportJob()->create([
        'design_id' => 'DAGwYwEDfW4',
        'format' => ['type' => ExportFormatType::JPG],
    ]))->toThrow(function (CanvaApiException $exception) {
        expect($exception)->not->toBeInstanceOf(CanvaNotFoundException::class)
            ->and($exception->getStatus())->toBe(502)
            ->and($exception->getErrorCode())->toBeNull()
            ->and($exception->getMessage())->toBe('Canva returned a 502 response.');
    });
});

test('a successful response missing the job key is reported as a malformed response', function () {
    $mockClient = new MockClient([
        GetDesignExportJob::class => MockResponse::make(['unexpected' => true], 200),
    ]);

    $client = (new Canva('client_id', 'client_secret', 'redirect_uri'))
        ->authenticateWithToken('access_token')
        ->withMockClient($mockClient);

    expect(fn () => $client->designExportJob()->get('be67f84b-fe7d-4993-bf98-6325d436e810'))
        ->toThrow(
            CanvaMalformedResponseException::class,
            'Canva returned a 200 response without the expected "job" key.',
        );
});

test('a successful response that is not json is reported as a malformed response', function () {
    $mockClient = new MockClient([
        GetDesignExportJob::class => MockResponse::make('not json at all', 200),
    ]);

    $client = (new Canva('client_id', 'client_secret', 'redirect_uri'))
        ->authenticateWithToken('access_token')
        ->withMockClient($mockClient);

    expect(fn () => $client->designExportJob()->get('be67f84b-fe7d-4993-bf98-6325d436e810'))
        ->toThrow(CanvaMalformedResponseException::class);
});
