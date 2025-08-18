<?php

namespace Canva;

use Canva\Authentications\CanvaOAuth;
use Canva\Resources\DesignResource;
use Canva\Resources\DesignExportJobResource;
use Canva\Resources\UserResource;
use Saloon\Http\Auth\AccessTokenAuthenticator;
use Saloon\Http\Connector;
use DateTimeImmutable;

abstract class Canva extends Connector
{
    /**
     * The base URL for the Canva API.
     *
     * @return string
     */
   public function resolveBaseUrl(): string
    {
        return 'https://api.canva.com/rest/';
    }

    /**
     * The default headers for the Canva API requests.
     *
     * @return array<string, string>
     */
    public function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    public static function oauth(string $clientId, string $clientSecret, string $redirectUri): CanvaOAuth
    {
       return new CanvaOAuth($clientId, $clientSecret, $redirectUri);
    }

    public function authenticateWithToken(string $token, ?string $refreshToken = null, ?DateTimeImmutable $expires_at = null): static
    {
        $authenticator = new AccessTokenAuthenticator(
            $token,
            $refreshToken,
            $expires_at
        );

        return $this->authenticate($authenticator);
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
}
