<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveEligibility extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'leave_policy_id',
        'amount',
        'period',
    ];

    /**
     * Get the leave policy associated with the leave eligibility.
     */
    public function leave_policy()
    {
        return $this->belongsTo(LeavePolicy::class);
    }
}
