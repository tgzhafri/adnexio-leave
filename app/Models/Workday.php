<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workday extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursay',
        'Friday',
        'Saturday',
        'Sunday',
    ];

    /**
     * Get the company that the workday belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
