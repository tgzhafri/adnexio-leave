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


};
