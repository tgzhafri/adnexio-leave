<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class LeavePolicyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'name' => $this->name,
            'abbreviation' => $this->abbreviation,
            'description' => $this->description,
            'color' => $this->color,
            'document_required' => $this->document_required,
            'reason_required' => $this->reason_required,
            'half_day_option' => $this->half_day_option,
            'cycle_period' => $this->cycle_period,
            'eligible_amount' => $this->eligible_amount,
            'eligible_period' => $this->eligible_period,
            'accrual_option' => $this->accrual_option,
            'accrual_happen' => $this->accrual_happen,
            'approval_route_id' => $this->approval_route_id,
            'leave_quota_amount' => $this->leave_quota_amount,
            'leave_quota_unit' => $this->leave_quota_unit,
            'leave_quota_category' => $this->leave_quota_category,
            'restriction_amount' => $this->restriction_amount,
            'day_prior' => $this->day_prior,
            'carry_forward_amount' => $this->carry_forward_amount,
            'carry_forward_expiry' => $this->carry_forward_expiry,
            'leave_credit_instant_use' => $this->leave_credit_instant_use,
            'leave_credit_expiry_amount' => $this->leave_credit_expiry_amount,
            'leave_credit_expiry_period' => $this->leave_credit_expiry_period,
            'status' => $this->status,
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_at' => Carbon::parse($this->updated_at)->toDateTimeString(),
        ];
    }
}
