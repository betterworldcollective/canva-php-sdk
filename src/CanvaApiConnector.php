<?php

namespace Canva;

use Canva\Resources\DesignExportJobResource;
use Canva\Resources\DesignResource;
use Canva\Resources\KeyResource;
use Canva\Resources\UserResource;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;

class CanvaApiConnector extends Connector
{
    public function __construct(protected readonly string $token) {}

    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->token);
    }

    public function resolveBaseUrl(): string
    {
        return 'https://api.canva.com/rest';
    }

    public function design(): DesignResource
    {
        return new DesignResource($this);
    }

    public function designExportJob(): DesignExportJobResource
    {
        return new DesignExportJobResource($this);
    }

    public function user(): UserResource
    {
        return new UserResource($this);
    }

    public function keys(): KeyResource
    {
        return new KeyResource($this);
    }
}
