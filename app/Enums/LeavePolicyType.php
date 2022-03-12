<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LeavePolicyType extends Enum
{
    const WithoutEntitlement = 'without_entitlement';
    const WithEntitlement = 'with_entitlement';
    const LeaveCredit = 'leave_credit';
}
