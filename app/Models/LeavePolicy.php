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
        'company_id',
        'name',
        'abbreviation',
        'description',
        'color',
        'icon',
        'cycle_period',
        'accrual_option',
        'accrual_happen',
        'approval_config_id',
        'carry_forward_amount',
        'carry_forward_expiry',
        'leave_credit',
        'leave_credit_expiry',
        'daily_quota',
        'restriction_amount',
        'proof_required',
        'half_day_option',
        'status',
    ];

    /**
     * Get the company that the leave policy belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the approval config that the leave policy belongs to.
     */
    public function approvalConfig()
    {
        return $this->belongsTo(ApprovalConfig::class);
    }

    /**
     * Get the leave eligibility associated with the leave policy.
     */
    public function leaveEligibility()
    {
        return $this->hasOne(LeaveEligibility::class);
    }

    /**
     * Get the leave eligibility associated with the leave policy.
     */
    public function entitlement()
    {
        return $this->hasOne(Entitlement::class);
    }

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
    public function leaveApplication()
    {
        return $this->hasMany(LeaveApplication::class);
    }
}
