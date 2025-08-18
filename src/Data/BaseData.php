<?php

namespace Canva\Data;

use Canva\Contracts\Data;

abstract class BaseData implements Data
{
    /**
     * @template TData of BaseData
     *
     * @param array<string, mixed> $dataOrigin
     * @param string $key
     * @param class-string<TData> $dtoClass
     * @return null|TData
     */
    public static function convertToData(array $dataOrigin, string $key, string $dtoClass)
    {
        $data = data_get($dataOrigin, $key);

        if (is_array($data)) {
            /** @var TData $dto */
            $dto = $dtoClass::from($data);

            return $dto;
        }

        return null;
    }
}
