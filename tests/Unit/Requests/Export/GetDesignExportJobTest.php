<?php

namespace Canva\Tests\Unit\Requests\Export;

use Canva\Requests\Export\GetDesignExportJob;
use Saloon\Enums\Method;

beforeEach(function () {
    $this->exportId = '123';
    $this->request = new GetDesignExportJob($this->exportId);
});

test('constructor sets properties', function () {
    expect($this->request)->toBeInstanceOf(GetDesignExportJob::class);
});

test('resolve endpoint', function () {
    $endpoint = $this->request->resolveEndpoint();

    expect($endpoint)->toBe('/v1/exports/123');
});

test('method is GET', function () {
    $method = $this->request->getMethod();

    expect($method)->toBe(Method::GET);
});
