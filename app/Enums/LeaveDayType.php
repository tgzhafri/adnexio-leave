<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LeaveDayType extends Enum
{
    const FullDay =   'full_day';
    const AM =   'am';
    const PM =   'pm';
    const OffDay = 'off_day';
    const RestDay = 'rest_day';
}
