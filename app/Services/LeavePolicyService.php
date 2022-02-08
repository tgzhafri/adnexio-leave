<?php

namespace App\Services;

use App\Models\ApprovalConfig;
use App\Models\LeaveEligibility;
use App\Models\LeaveEntitlement;
use App\Models\LeavePolicy;
use Illuminate\Support\Collection;
use Illuminate\Session\SessionManager;

class LeavePolicyService
{
    protected LeavePolicy $leavePolicy;
    protected LeaveEligibility $leaveEligibility;
    protected LeaveEntitlement $leaveEntitlement;
    protected ApprovalConfig $approvalConfig;

    public function __construct(LeavePolicy $leavePolicy, LeaveEligibility $leaveEligibility, LeaveEntitlement $leaveEntitlement, ApprovalConfig $approvalConfig)
    {
        $this->leavePolicy = $leavePolicy;
        $this->leaveEligibility = $leaveEligibility;
        $this->leaveEntitlement = $leaveEntitlement;
        $this->approvalConfig = $approvalConfig;
    }

    public function create($companyId, $name, $abbreviation, $description, $color, $icon, $cyclePeriod, $accrualOption, $accrualHappens, $approvalConfigId, $carryForwardAmount, $carryForwardExpiry, $leaveCredit, $leaveCreditExpiry, $dailyQuota, $restrictionAmount, $proofRequired, $halfDayOption, $status): void
    {
        $policy = $this->createPolicy($companyId, $name, $abbreviation, $description, $color, $icon, $cyclePeriod, $accrualOption, $accrualHappens, $approvalConfigId, $carryForwardAmount, $carryForwardExpiry, $leaveCredit, $leaveCreditExpiry, $dailyQuota, $restrictionAmount, $proofRequired, $halfDayOption, $status);


    }

    protected function createPolicy()
}
