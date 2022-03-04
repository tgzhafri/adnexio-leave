<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entitlement extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'staff_id',
        'leave_policy_id',
        'cycle_start_date',
        'cycle_end_date',
        'amount',
        'balance',
    ];

    /**
     * Get the carry forward associated with the leave application.
     */
    public function carryForward()
    {
        return $this->hasOne(CarryForward::class);
    }

    /**
     * Get the leave credit associated with the leave application.
     */
    public function leaveCredit()
    {
        return $this->hasOne(LeaveCredit::class);
    }

    /**
     * Get the leave policy associated with the entitlement.
     */
    public function leavePolicy()
    {
        return $this->belongsTo(LeavePolicy::class);
    }

    /**
     * Get the staff associated with the entitlement.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
