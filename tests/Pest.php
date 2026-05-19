<?php

use Saloon\MockConfig;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Canva\Requests\App\GetKeys;
use Canva\Requests\Design\CreateDesign;
use Canva\Requests\Export\CreateDesignExportJob;
use Canva\Requests\Export\GetDesignExportJob;
use Saloon\Config;
use Canva\Requests\User\UserProfile;

MockConfig::setFixturePath('tests/Fixtures');
MockConfig::throwOnMissingFixtures();
Config::preventStrayRequests();

MockClient::global([
    // Designs
    CreateDesign::class => MockResponse::fixture('create-design'),

    // Exports
    CreateDesignExportJob::class => MockResponse::fixture('create-design-export-job'),
    GetDesignExportJob::class => MockResponse::fixture('get-design-export-job'),

    // User
    UserProfile::class => MockResponse::fixture('get-current-user-profile'),

    // App
    GetKeys::class => MockResponse::make([
        'keys' => [
            ['kid' => 'k1', 'kty' => 'EC', 'crv' => 'P-256', 'x' => 'x', 'y' => 'y'],
        ],
    ], 200),
]);
