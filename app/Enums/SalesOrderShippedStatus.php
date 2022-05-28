<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Unknown()
 * @method static static Waiting()
 * @method static static Processing()
 * @method static static Completed()
 */
final class SalesOrderShippedStatus extends Enum
{
    const Unknown =   0;
    const Waiting =   1;
    const Processing = 2;
    const Completed = 3;
}
