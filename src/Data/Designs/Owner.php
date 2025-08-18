<?php

namespace Canva\Data\Designs;

use Canva\Data\BaseData;

/**
 * @phpstan-type OwnerDataResponse array{
 *     user_id: string,
 *     team_id: string
 * }
 */
class Owner extends BaseData
{
    public function __construct(
        public string $user_id,
        public string $team_id,
    ) {
        //
    }

    /**
     * @param OwnerDataResponse $data
     */
    public static function from(array $data): self
    {
        return new self(
            user_id: $data['user_id'],
            team_id: $data['team_id']
        );
    }
}
