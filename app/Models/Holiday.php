<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'company_id',
        'day',
        'date',
        'location',
        'type',
    ];

    /**
     * Get the company that the holiday belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
