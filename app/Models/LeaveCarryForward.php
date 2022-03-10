<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveCarryForward extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'entitlement_id',
        'from_year',
        'expiry_date',
        'amount',
        'balance'
    ];
    /**
     * Get the approval associated with the leave application.
     */
    public function leaveEntitlement()
    {
        return $this->belongsTo(LeaveEntitlement::class);
    }
}
