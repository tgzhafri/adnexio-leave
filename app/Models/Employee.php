<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'employee_no',
        'dob',
        'employment_type',
        'profile_photo',
        'joined_date',
        'gender',
        'marital_status',
        'status',
    ];

    /**
     * Get the user associated with the employee.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the job title associated with the employee.
     */
    public function job_title()
    {
        return $this->hasOne(JobTitle::class);
    }

    /**
     * Get the entitlement for the employee.
     */
    public function entitlements()
    {
        return $this->hasMany(Entitlement::class);
    }

    /**
     * Get the entitlement for the employee.
     */
    public function leave_applications()
    {
        return $this->hasMany(LeaveApplication::class);
    }
}
