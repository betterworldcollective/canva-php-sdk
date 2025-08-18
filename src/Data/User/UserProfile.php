<?php

namespace Canva\Data\User;

use Canva\Data\BaseData;

/**
 * @phpstan-type UserProfileResource array{
 *    display_name: string
 * }
 */
class UserProfile extends BaseData
{
    public function __construct(
        public string $display_name
    ) {
        //
    }

    /**
     * Create an Error instance from an associative array.
     *
     * @param UserProfileResource $data
     */
    public static function from(array $data): self
    {
        return new self(
            display_name: $data['display_name'],
        );
    }
}
