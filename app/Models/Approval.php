<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Approval extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_approval',
        'first_approval_status',
        'first_approval_timestamp',
        'second_approval',
        'second_approval_status',
        'second_approval_timestamp',
        'third_approval',
        'third_approval_status',
        'third_approval_timestamp',
    ];

    /**
     * Get the leave application that the approval belongs to.
     */
    public function leave_application()
    {
        return $this->belongsTo(LeaveApplication::class);
    }
}
