<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveCredit extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'entitlement_id',
        'amount',
        'completed_date',
        'expiry_date',
        'status',
        'assign_by',
        'assign_to',
        'tag',
        'acknowledgment_superior',
        'acknowledgment_employee',
    ];

    /**
     * Get the entitlement associated with the leave credit.
     */
    public function entitlement()
    {
        return $this->belongsTo(Entitlement::class);
    }
}
