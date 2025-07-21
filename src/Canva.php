<?php

namespace Canva;

use Canva\Authentications\CanvaOAuth;
use Saloon\Http\Connector;

abstract class Canva extends Connector
{
    /**
     * The base URL for the Canva API.
     *
     * @return string
     */
   public function resolveBaseUrl(): string
    {
        return 'https://www.canva.com/api/v1/';
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

    public static function oauth(string $clientId, string $clientSecret, string $redirectUri): self
    {
       return new CanvaOAuth($clientId, $clientSecret, $redirectUri);
    }
}
