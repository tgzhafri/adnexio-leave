<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveApplication extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'approval_id',
        'leave_policy_id',
        'status',
        'description',
        'attachment',
    ];

    /**
     * Get the approval associated with the leave application.
     */
    public function approval()
    {
        return $this->hasOne(Approval::class);
    }

    /**
     * Get the employee associated with the leave application.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the leave date associated with the leave application.
     */
    public function leaveDate()
    {
        return $this->hasOne(LeaveDate::class);
    }

    /**
     * Get the employee associated with the leave application.
     */
    public function leavePolicy()
    {
        return $this->belongsTo(LeavePolicy::class);
    }
}
