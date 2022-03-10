<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeavePolicy extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'company_id',
        'name',
        'abbreviation',
        'description',
        'color',
        'type',
        'attachment_required',
        'reason_required',
        'half_day_option',
        'credit_deduction',
        'credit_expiry_amount',
        'credit_expiry_period',
        'cycle_period',
        'eligible_amount',
        'eligible_period',
        'accrual_option',
        'accrual_happen',
        // 'approval_route_id',
        'quota_amount',
        'quota_unit',
        'quota_category',
        'restriction_amount',
        'day_prior',
        'carry_forward_amount',
        'carry_forward_expiry',
        'status',
    ];

    // /**
    //  * Get the company that the leave policy belongs to.
    //  */
    // public function company()
    // {
    //     return $this->belongsTo(Company::class);
    // }

    // /**
    //  * Get the approval route that the leave policy belongs to.
    //  */
    // public function approvalRoute()
    // {
    //     return $this->belongsTo(ApprovalRoute::class);
    // }

    /**
     * Get the leave eligibility associated with the leave policy.
     */
    public function leaveEntitlement()
    {
        return $this->hasMany(LeaveEntitlement::class);
    }

    /**
     * Get the leave eligibility associated with the leave policy.
     */
    public function leavePolicyEntitlement()
    {
        return $this->hasMany(LeavePolicyEntitlement::class);
    }

    /**
     * Get the category associated with the leave policy.
     */
    public function leaveCategory()
    {
        return $this->hasMany(LeaveCategory::class);
    }

    /**
     * Get the leave eligibility associated with the leave policy.
     */
    public function leaveRequest()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}
