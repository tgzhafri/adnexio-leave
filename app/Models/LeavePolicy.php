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
        'with_entitlement',
        'document_required',
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
        'approval_route_id',
        'leave_quota_amount',
        'leave_quota_unit',
        'leave_quota_category',
        'restriction_amount',
        'day_prior',
        'carry_forward_amount',
        'carry_forward_expiry',
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
     * Get the approval route that the leave policy belongs to.
     */
    public function approvalRoute()
    {
        return $this->belongsTo(ApprovalRoute::class);
    }

    /**
     * Get the leave eligibility associated with the leave policy.
     */
    public function entitlement()
    {
        return $this->hasMany(Entitlement::class);
    }

    /**
     * Get the leave eligibility associated with the leave policy.
     */
    public function leaveEntitlement()
    {
        return $this->hasMany(LeaveEntitlement::class);
    }

    /**
     * Get the category associated with the leave policy.
     */
    public function category()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get the leave eligibility associated with the leave policy.
     */
    public function leaveRequest()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}
