<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Waiting()
 * @method static static Processing()
 * @method static static Completed()
 */
final class SalesOrderShippedStatus extends Enum
{
    const Waiting =   0;
    const Processing = 1;
    const Completed = 2;
}
