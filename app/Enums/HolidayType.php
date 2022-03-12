<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class HolidayType extends Enum
{
    const Custom = 'custom';
    const Public = 'public';
}
