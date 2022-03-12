<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LeavePeriodType extends Enum
{
    const Day =   'day';
    const Week =   'week';
    const Month = 'month';
    const Monthly = 'monthly';
    const Year = 'year';
    const Yearly = 'yearly';
    const StartMonth = 'start_month';
    const EndMonth = 'end_month';
    const StartYear = 'start_year';
    const EndYear = 'end_year';
}
