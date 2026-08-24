<?php

namespace Canva\Requests\App;

use Canva\Data\App\Key;
use Canva\Exceptions\CanvaApiException;
use Canva\Traits\DecodesResponses;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Http\SoloRequest;

/**
 * getAppJwks
 *
 * Returns the Json Web Key Set (public keys) of an app. These keys are used to
 * verify JWTs sent to app backends. This endpoint is unauthenticated, so it is
 * implemented as a SoloRequest and can be sent without a connector.
 *
 * @phpstan-import-type KeyData from Key
 */
class GetKeys extends SoloRequest
{
    use DecodesResponses;

    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return 'https://api.canva.com/rest/v1/connect/keys';
    }

    /**
     * @throws CanvaApiException
     */
    public function createDtoFromResponse(Response $response): Key
    {
        /** @var KeyData $responseBody */
        $responseBody = $this->payload($response);

        return Key::from($responseBody);
    }
}
