<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveDate extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'leave_application_id',
        'date',
        'time',
    ];

    /**
     * Get the leave application associated with the leave date.
     */
    public function leave_application()
    {
        return $this->belongsTo(LeaveApplication::class);
    }
}
