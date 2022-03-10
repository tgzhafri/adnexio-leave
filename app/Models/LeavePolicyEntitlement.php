<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeavePolicyEntitlement extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'leave_policy_id',
        'layer',
        'amount',
        'start_year_of_service',
        'end_year_of_service',
    ];

    /**
     * Get the leave policy associated with the leave eligibility.
     */
    public function leavePolicy()
    {
        return $this->belongsTo(LeavePolicy::class);
    }
}
