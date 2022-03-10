<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeavePolicyEntitlementResource extends JsonResource
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
            'leave_policy_id' => $this->leave_policy_id,
            'layer' => $this->layer,
            'amount' => $this->amount,
            'start_year_of_service' => $this->start_year_of_service,
            'end_year_of_service' => $this->end_year_of_service,
        ];
    }
}
