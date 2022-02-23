<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Employee extends Authenticatable
{
    use HasFactory, SoftDeletes, HasRoles, HasRecursiveRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'name',
        'user_id',
        'department_id',
        'position_id',
        'parent_id',
        'job_title',
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
     * Get the poisition associated with the employee.
     */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Get the department associated with the employee.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
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
    public function leaveRequest()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}
