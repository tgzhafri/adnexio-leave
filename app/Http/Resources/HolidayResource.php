<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class HolidayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $date = Carbon::parse($this->date)->format('d-M-Y');

        return [
            'id' => $this->id,
            // 'company_id' => $this->company_id,
            'name' => $this->name,
            'day' => $this->day,
            'date' => $date,
            'location' => $this->location,
            'type' => $this->type,
            'holiday_type' => $this->holiday_type,
            'status' => $this->status
        ];
    }
}
