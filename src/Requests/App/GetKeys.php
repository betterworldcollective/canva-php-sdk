<?php

namespace Canva\Requests\App;

use Canva\Data\App\Key;
use JsonException;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

/**
 * getAppJwks
 *
 * Returns the Json Web Key Set (public keys) of an app. These keys are used to
 * verify JWTs sent to app
 * backends.
 *
 * @phpstan-import-type KeyData from Key
 */
class GetKeys extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "v1/connect/keys";
	}

    /**
     * @throws JsonException
     */
    public function createDtoFromResponse(Response $response): Key
    {
        /** @var KeyData $responseBody */
        $responseBody = $response->json();

        return Key::from($responseBody);
    }
}
