<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EntitlementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'leave_policy_id' => $this->leave_policy_id,
            'cycle_start_date' => $this->cycle_start_date,
            'cycle_end_date' => $this->cycle_end_date,
            'amount' => $this->amount,
            'balance' => $this->balance,
        ];
    }
}
