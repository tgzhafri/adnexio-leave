<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LeaveCategoryType extends Enum
{
    const Gender = 'gender';
    const MaritalStatus = 'marital_status';
    const EmploymentType = 'employment_type';
    const Male = 'male';
    const Female = 'female';
    const Single = 'single';
    const Married = 'married';
    const Divorced = 'divorced';
    const Widowed = 'widowed';
    const Permanent = 'permanent';
    const Contract = 'contract';
    const Intern = 'intern';
}
