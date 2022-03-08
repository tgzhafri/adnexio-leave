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
            // 'company_id' => $this->company_id,
            'name' => $this->name,
            'abbreviation' => $this->abbreviation,
            'description' => $this->description,
            'color' => $this->color,
            'type' => $this->type,
            'attachment_required' => $this->attachment_required,
            'reason_required' => $this->reason_required,
            'half_day_option' => $this->half_day_option,
            'credit_deduction' => $this->credit_deduction,
            'credit_expiry_amount' => $this->credit_expiry_amount,
            'credit_expiry_period' => $this->credit_expiry_period,
            'cycle_period' => $this->cycle_period,
            'eligible_amount' => $this->eligible_amount,
            'eligible_period' => $this->eligible_period,
            'accrual_option' => $this->accrual_option,
            'accrual_happen' => $this->accrual_happen,
            // 'approval_route_id' => $this->approval_route_id,
            'quota_amount' => $this->quota_amount,
            'quota_unit' => $this->quota_unit,
            'quota_category' => $this->quota_category,
            'restriction_amount' => $this->restriction_amount,
            'day_prior' => $this->day_prior,
            'carry_forward_amount' => $this->carry_forward_amount,
            'carry_forward_expiry' => $this->carry_forward_expiry,
            'status' => $this->status,
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_at' => Carbon::parse($this->updated_at)->toDateTimeString(),
        ];
    }
}
