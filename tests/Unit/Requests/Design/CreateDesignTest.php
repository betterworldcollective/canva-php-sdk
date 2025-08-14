<?php

namespace Canva\Tests\Unit\Requests\Design;

use Canva\Requests\Design\CreateDesign;
use Saloon\Enums\Method;

beforeEach(function () {
    $this->properties = [
        'design_type' => [
            'type' => 'custom',
            'width' => 1920,
            'height' => 1080
        ],
    ];
    $this->request = new CreateDesign($this->properties);
});

test('constructor sets properties', function () {
    expect($this->request)->toBeInstanceOf(CreateDesign::class);
});

test('resolve endpoint', function () {
    $endpoint = $this->request->resolveEndpoint();

    expect($endpoint)->toBe('/v1/designs');
});

test('method is POST', function () {
    $method = $this->request->getMethod();

    expect($method)->toBe(Method::POST);
});
