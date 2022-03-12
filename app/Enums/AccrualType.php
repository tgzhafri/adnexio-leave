<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class AccrualType extends Enum
{
    const FullAmount = 'full_amount';
    const ProrateFullAmount = 'full_amount_prorate';
    const Prorate = 'prorate';
}
