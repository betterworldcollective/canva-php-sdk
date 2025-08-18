<?php

namespace Canva\Contracts;

interface Data
{
    /**
     * Convert the data to an array.
     *
     * @param array<mixed, mixed> $data
     * @return self
     */
    public static function from(array $data): self;
}
