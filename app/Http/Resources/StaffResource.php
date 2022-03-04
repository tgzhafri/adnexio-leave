<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'company_id' => $this->company_id,
            'user_id' => $this->user_id,
            'position_id' => $this->position_id,
            'role' => $this->role,
            'employee_no' => $this->employee_no,
            'dob' => $this->dob,
            'job_title' => $this->job_title,
            'employment_type' => $this->employment_type,
            'profile_photo' => $this->profile_photo,
            'joined_date' => $this->joined_date,
            'gender' => $this->gender,
            'marital_status' => $this->marital_status,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
