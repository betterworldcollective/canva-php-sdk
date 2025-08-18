<?php

use Saloon\MockConfig;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Canva\Requests\Design\CreateDesign;
use Canva\Requests\Export\CreateDesignExportJob;
use Canva\Requests\Export\GetDesignExportJob;
use Saloon\Config;

MockConfig::setFixturePath('tests/Fixtures');
MockConfig::throwOnMissingFixtures();
Config::preventStrayRequests();

MockClient::global([
    CreateDesign::class => MockResponse::fixture('create-design'),
    CreateDesignExportJob::class => MockResponse::fixture('create-design-export-job'),
    GetDesignExportJob::class => MockResponse::fixture('get-design-export-job'),
]);
