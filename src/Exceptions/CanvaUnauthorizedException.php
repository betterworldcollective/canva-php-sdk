<?php

namespace Canva\Exceptions;

/**
 * Canva rejected our credentials (401) or the token lacks the scope for this call (403).
 */
class CanvaUnauthorizedException extends CanvaApiException {}
