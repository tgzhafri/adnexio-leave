<?php

namespace App\Models;

use App\Enums\AccrualType;
use App\Enums\LeavePeriodType;
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
        'prorate',
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
        if ($leavePolicy->accrual_type == AccrualType::FullAmount) {
            return [
                'amount' => $this->amount,
                'balance' => $this->balance
            ];
        }
        if ($leavePolicy->accrual_type == AccrualType::Prorate) {
            $currentMonth = Carbon::now()->format('m');
            $prevMonth = Carbon::now()->subMonth()->format('m');
            $currentDate = Carbon::now()->startOfDay()->format('Y-m-d');
            $startOfCurrentMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endOfCurrentMonth = Carbon::now()->endOfMonth()->format('Y-m-d');
            $leaveDate = LeaveDate::whereHas('leaveRequest', function ($query) {
                $query->where([
                    ['leave_policy_id', $this->leave_policy_id],
                    ['staff_id', $this->staff_id],
                ]);
            })->with(['leaveRequest' => function ($query) {
                return $query->select(['id', 'staff_id', 'leave_policy_id']);
            }])->get()->count();

            if ($leavePolicy->accrual_happen == LeavePeriodType::StartMonth) {
                $prorated = $this->balance / 12 * $currentMonth;
                $bal = $prorated - $leaveDate;
                LeaveEntitlement::whereId($this->id)->update([
                    'prorate' => $bal
                ]);
                if ($currentDate >= $startOfCurrentMonth) {
                    return [
                        'prorate' => $prorated,
                    ];
                }
            }
            if ($leavePolicy->accrual_happen == LeavePeriodType::EndMonth) {
                if ($currentDate >= $endOfCurrentMonth) {
                    $prorated = $this->balance / 12 * $currentMonth;
                    return [
                        'prorate' => $prorated,

                    ];
                } else {
                    $prorated = $this->balance / 12 * $prevMonth;
                    return [
                        'prorate' => $prorated,

                    ];
                }
            }
        }
    }
}
