<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static GLAccount()
 * @method static static Item()
 * @method static static Resource()
 * @method static static FixedAsset()
 * @method static static ChargeItem()
 */
final class SalesLineType extends Enum
{

    const GLAccount = 1;
    const Item = 2;
    const Resource = 3;
    const FixedAsset = 4;
    const ChargeItem = 5;
}
