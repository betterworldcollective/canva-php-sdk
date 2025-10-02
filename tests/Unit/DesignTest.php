<?php

use Canva\Canva;
use Canva\Data\Designs\Design;
use Canva\Enums\DesignType;

$client = (new Canva('client_id', 'client_secret', 'redirect_uri'))->authenticateWithToken('access_token');

test('can create a design', function () use ($client) {
    $design = $client->design()->create([
       'design_type' => [
           'type' => DesignType::Custom,
           'width' => 800,
           'height' => 600,
       ],
        'title' => 'Another design 101!!!',
    ]);

    expect($design)->toBeInstanceOf(Design::class);
    expect($design)->toHaveKey('id')
        ->and($design->id)->toBe('DAGwYwEDfW4')
        ->and($design)->toHaveKey('title')
        ->and($design->title)->toBe('Another design 101!!!')
        ->and($design)->toHaveKey('owner')
        ->and($design)->toHaveKey('urls');

});
