<?php

namespace Canva\Exceptions;

/**
 * Canva could not find the resource we asked for: a design, an export job, an asset.
 * Usually the identifier we sent is wrong, so retrying the same request will not help.
 */
class CanvaNotFoundException extends CanvaApiException {}
