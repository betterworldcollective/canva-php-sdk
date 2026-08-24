<?php

namespace Canva\Traits;

use Canva\Exceptions\CanvaApiException;
use Canva\Exceptions\CanvaMalformedResponseException;
use Saloon\Http\Response;
use Throwable;

/**
 * Turns a Saloon response into the array a DTO is hydrated from.
 *
 * The connector does not throw on HTTP errors, so an error body -- which carries `code` and
 * `message` instead of the resource -- reaches DTO hydration and used to surface as an
 * "Undefined array key" error from deep inside it. Reading the body through payload() means a
 * failed request fails as a CanvaApiException that still knows its HTTP status.
 */
trait DecodesResponses
{
    /**
     * @param  string|null  $key  The key the resource lives under, or null for the whole body.
     * @return array<array-key, mixed>
     *
     * @throws CanvaApiException
     */
    protected function payload(Response $response, ?string $key = null): array
    {
        if ($response->failed()) {
            throw CanvaApiException::fromResponse($response);
        }

        try {
            // Cast rather than assert: a successful response whose body is a JSON scalar
            // becomes an array that simply has no resource key, which the check below reports.
            $body = (array) $response->json();
        } catch (Throwable $exception) {
            throw new CanvaMalformedResponseException(
                'Canva returned a response that could not be decoded as JSON.',
                $response->status(),
                previous: $exception,
            );
        }

        if ($key === null) {
            return $body;
        }

        $resource = $body[$key] ?? null;

        if (! is_array($resource)) {
            throw new CanvaMalformedResponseException(
                sprintf('Canva returned a %d response without the expected "%s" key.', $response->status(), $key),
                $response->status(),
            );
        }

        return $resource;
    }
}
