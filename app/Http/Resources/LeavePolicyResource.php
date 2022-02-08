<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'company_id' => $this->company_id,
            'name' => $this->name,
            'abbreviation' => $this->abbreviation,
            'description' => $this->description,
            'color' => $this->color,
            'icon' => $this->icon,
            'cycle_period' => $this->cycle_period,
            'accrual_option' => $this->accrual_option,
            'accrual_happen' => $this->accrual_happen,
            'approval_config_id' => $this->approval_config_id,
            'carry_forward_amount' => $this->carry_forward_amount,
            'carry_forward_expiry' => $this->carry_forward_expiry,
            'leave_credit' => $this->leave_credit,
            'leave_credit_expiry' => $this->leave_credit_expiry,
            'daily_quota' => $this->daily_quota,
            'restriction_amount' => $this->restriction_amount,
            'proof_required' => $this->proof_required,
            'description_required' => $this->description_required,
            'half_day_option' => $this->half_day_option,
            'status' => $this->status,
        ];
    }
}
