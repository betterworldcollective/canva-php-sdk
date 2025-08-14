<?php

namespace Canva\Tests\Unit\Requests\Export;

use Canva\Requests\Export\CreateDesignExportJob;
use Saloon\Enums\Method;

beforeEach(function () {
    $this->properties = [
        'design_id' => '123',
        'format' => [
            'type' => 'jpg'
        ]
    ];
    $this->request = new CreateDesignExportJob($this->properties);
});

test('constructor sets properties', function () {
    expect($this->request)->toBeInstanceOf(CreateDesignExportJob::class);
});

test('resolve endpoint', function () {
    $endpoint = $this->request->resolveEndpoint();

    expect($endpoint)->toBe('/v1/exports');
});

test('method is POST', function () {
    $method = $this->request->getMethod();

    expect($method)->toBe(Method::POST);
});
