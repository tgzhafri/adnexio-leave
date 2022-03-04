<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveCategory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'leave_policy_id',
        'name',
        'status',
    ];

    /**
     * Get the leave policy that the category belongs to.
     */
    public function leavePolicy()
    {
        return $this->belongsTo(LeavePolicy::class);
    }
}
