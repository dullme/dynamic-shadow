<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Planning()
 * @method static static Quote()
 * @method static static Open()
 * @method static static Completed()
 */
final class ProjectStatus extends Enum
{

    const Planning = 0;
    const Quote = 1;
    const Open = 2;
    const Completed = 3;
}
