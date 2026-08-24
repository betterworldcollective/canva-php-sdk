<?php

namespace Canva\Exceptions;

/**
 * Canva answered successfully but the body is not shaped the way the API documents it --
 * unreadable JSON, or the resource key a DTO is built from is missing.
 */
class CanvaMalformedResponseException extends CanvaApiException {}
