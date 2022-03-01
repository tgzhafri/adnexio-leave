<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Carbon;

class LeavePolicyCollection extends ResourceCollection
{
    public function with($request)
    {
        return [
            'success' => true,
            'message' => 'Retreived Index leave policy details successful',
            'code' => 200
        ];
    }
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'leave_policy' => $this->collection->map(function ($data) {
                    return [
                        'id' => $data->id,
                        'company_id' => $data->company_id,
                        'name' => $data->name,
                        'abbreviation' => $data->abbreviation,
                        'description' => $data->description,
                        'color' => $data->color,
                        'document_required' => $data->document_required,
                        'reason_required' => $data->reason_required,
                        'half_day_option' => $data->half_day_option,
                        'credit_deduction' => $data->credit_deduction,
                        'credit_expiry_amount' => $data->credit_expiry_amount,
                        'credit_expiry_period' => $data->credit_expiry_period,
                        'cycle_period' => $data->cycle_period,
                        'eligible_amount' => $data->eligible_amount,
                        'eligible_period' => $data->eligible_period,
                        'accrual_option' => $data->accrual_option,
                        'accrual_happen' => $data->accrual_happen,
                        'approval_route_id' => $data->approval_route_id,
                        'leave_quota_amount' => $data->leave_quota_amount,
                        'leave_quota_unit' => $data->leave_quota_unit,
                        'leave_quota_category' => $data->leave_quota_category,
                        'restriction_amount' => $data->restriction_amount,
                        'day_prior' => $data->day_prior,
                        'carry_forward_amount' => $data->carry_forward_amount,
                        'carry_forward_expiry' => $data->carry_forward_expiry,
                        'status' => $data->status,
                        'created_at' => Carbon::parse($data->created_at)->toDateTimeString(),
                        'updated_at' => Carbon::parse($data->updated_at)->toDateTimeString()
                    ];
                })
            ]
        ];
    }
}
