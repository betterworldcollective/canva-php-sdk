<?php

namespace Canva\Exceptions;

/**
 * Canva is rate limiting us. The same request may succeed once the window resets.
 */
class CanvaRateLimitException extends CanvaApiException {}
