<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveEntitlement extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'staff_id',
        'leave_policy_id',
        'cycle_start_date',
        'cycle_end_date',
        'amount',
        'balance',
    ];

    /**
     * Get the carry forward associated with the leave application.
     */
    public function leaveCarryForward()
    {
        return $this->hasOne(LeaveCarryForward::class);
    }

    /**
     * Get the leave credit associated with the leave application.
     */
    public function leaveCredit()
    {
        return $this->hasOne(LeaveCredit::class);
    }

    /**
     * Get the leave policy associated with the entitlement.
     */
    public function leavePolicy()
    {
        return $this->belongsTo(LeavePolicy::class);
    }

    /**
     * Get the staff associated with the entitlement.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function entitlement()
    {
        $leavePolicy = LeavePolicy::whereId($this->leave_policy_id)->first();
        if ($leavePolicy->accrual_option == 'full_amount') {
            return [
                'amount' => $this->amount,
                'balance' => $this->balance
            ];
        }
        if ($leavePolicy->accrual_option == 'prorate') {
            $currentMonth = Carbon::now()->format('m');
            $prevMonth = Carbon::now()->subMonth()->format('m');
            $currentDate = Carbon::now()->startOfDay()->format('Y-m-d');
            $startOfCurrentMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endOfCurrentMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

            if ($leavePolicy->accrual_happen == 'start_month') {
                if ($currentDate >= $startOfCurrentMonth) {
                    $prorated = $this->balance / 12 * $currentMonth;
                    return [
                        'amount' => $prorated,
                        'balance' => $prorated
                    ];
                }
            }
            if ($leavePolicy->accrual_happen == 'end_month') {
                if ($currentDate >= $endOfCurrentMonth) {
                    $prorated = $this->balance / 12 * $currentMonth;
                    return [
                        'amount' => $prorated,
                        'balance' => $prorated
                    ];
                } else {
                    $prorated = $this->balance / 12 * $prevMonth;
                    return [
                        'amount' => $prorated,
                        'balance' => $prorated
                    ];
                }
            }
        }
    }
}
