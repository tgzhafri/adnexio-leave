<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveRequest extends Model
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
        'status',
        'reason',
        'attachment',
    ];

    /**
     * Get the approval associated with the leave application.
     */
    public function leaveApproval()
    {
        return $this->hasOne(LeaveApproval::class);
    }

    /**
     * Get the staff associated with the leave application.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the leave date associated with the leave application.
     */
    public function leaveDate()
    {
        return $this->hasOne(LeaveDate::class);
    }

    /**
     * Get the leave credit associated with the leave application.
     */
    public function leaveCredit()
    {
        return $this->hasMany(LeaveDate::class);
    }

    /**
     * Get the staff associated with the leave application.
     */
    public function leavePolicy()
    {
        return $this->belongsTo(LeavePolicy::class);
    }
}
