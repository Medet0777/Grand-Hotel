<?php

namespace App\Casts;

use Spatie\DataTransferObject\Caster;


class FloatCast implements Caster
{
    public function cast(mixed $value): float
    {
        return (float) $value;
    }
}
