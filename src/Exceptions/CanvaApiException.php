<?php

namespace Canva\Exceptions;

use RuntimeException;
use Saloon\Http\Response;
use Throwable;

/**
 * Base class for the errors Canva's API reports back to us.
 *
 * The HTTP status and Canva's own error code are kept on the exception so callers can tell
 * a rejected request apart from an outage, instead of treating every failure the same way.
 */
class CanvaApiException extends RuntimeException
{
    public function __construct(
        string $message,
        protected int $status = 0,
        protected ?string $errorCode = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $status, $previous);
    }

    /**
     * Builds the most specific exception available for a failed response.
     */
    public static function fromResponse(Response $response): self
    {
        $status = $response->status();
        [$errorCode, $message] = self::extractError($response);

        $message ??= sprintf(
            'Canva returned a %d response%s.',
            $status,
            $errorCode === null ? '' : sprintf(' (%s)', $errorCode)
        );

        return match (true) {
            $status === 401, $status === 403 => new CanvaUnauthorizedException($message, $status, $errorCode),
            $status === 404 => new CanvaNotFoundException($message, $status, $errorCode),
            $status === 429 => new CanvaRateLimitException($message, $status, $errorCode),
            default => new self($message, $status, $errorCode),
        };
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Canva's own error code, such as `not_found` or `permission_denied`, when it sent one.
     */
    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    /**
     * Canva errors carry a machine readable `code` and a human readable `message`. A failed
     * request is not guaranteed to be JSON at all -- a gateway in front of the API can answer
     * with HTML -- so anything we cannot read falls back to null and the caller's own wording.
     *
     * @return array{0: string|null, 1: string|null}
     */
    private static function extractError(Response $response): array
    {
        try {
            $body = $response->json();
        } catch (Throwable) {
            return [null, null];
        }

        $errorCode = data_get($body, 'code');
        $message = data_get($body, 'message');

        return [
            is_string($errorCode) ? $errorCode : null,
            is_string($message) ? $message : null,
        ];
    }
}
