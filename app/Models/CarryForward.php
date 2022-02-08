<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarryForward extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'entitlement_id',
        'year',
        'expiry_date',
        'amount',
    ];
    /**
     * Get the approval associated with the leave application.
     */
    public function entitlement()
    {
        return $this->belongsTo(Entitlement::class);
    }
}
