<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApprovalRoute extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'layer_one',
        'layer_two',
        'layer_three',
    ];

    /**
     * Get the leave policy for the approval config.
     */
    public function leavePolicy()
    {
        return $this->hasMany(LeavePolicy::class);
    }
}
