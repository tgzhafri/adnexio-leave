<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApprovalConfig extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_approval',
        'second_approval',
        'third_approval',
    ];

    /**
     * Get the leave policy for the approval config.
     */
    public function leavePolicy()
    {
        return $this->hasMany(LeavePolicy::class);
    }
}
