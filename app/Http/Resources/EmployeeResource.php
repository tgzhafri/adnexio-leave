<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    // public function toArray($request)
    // {
    //     return parent::toArray($request);

    // }

    public function toArray($request): array
    {
        return [
            'company_id' => $this->company_id,
            'user_id' => $this->user_id,
            'employee_no' => $this->employee_no,
            'dob' => $this->dob,
            'employment_type' => $this->employment_type,
            'profile_photo' => $this->profile_photo,
            'joined_date' => $this->joined_date,
            'gender' => $this->gender,
            'marital_status' => $this->marital_status,
            'status' => $this->status,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,

        ];
    }
}
