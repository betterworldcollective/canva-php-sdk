<?php

namespace Canva;

use Canva\Authentications\CanvaOAuth;
use Canva\Authentications\CanvaToken;
use Saloon\Http\Connector;

abstract class Canva extends Connector
{

    private ?string $codeVerifier = null;
    private ?string $codeChallenge = null;

    /**
     * The default OAuth configuration for the Canva API.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUri
     * @return CanvaOAuth
     */
    public static function oauth(string $clientId, string $clientSecret, string $redirectUri): CanvaOAuth
    {
        return new CanvaOAuth($clientId, $clientSecret, $redirectUri);
    }

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
}