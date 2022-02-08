<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'organisation_no',
        'logo',
        'address',
    ];

    /**
     * Get the users for the company.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the holidays for the company.
     */
    public function holidays()
    {
        return $this->hasMany(Holiday::class);
    }

    /**
     * Get the workdays for the company.
     */
    public function workdays()
    {
        return $this->hasMany(Workday::class);
    }

    /**
     * Get the leave policies for the company.
     */
    public function leavePolicy()
    {
        return $this->hasMany(LeavePolicy::class);
    }
}
