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
        'requested',
        'granted',
        'rejected',
        'utilised',
        'outstanding',
        'expiry_date',
        'status',
    ];

    /**
     * Get the entitlement associated with the leave credit.
     */
    public function entitlement()
    {
        return $this->belongsTo(Entitlement::class);
    }
}
