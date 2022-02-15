<?php

namespace App\Services;

use App\Http\Requests\LeavePolicy\StoreRequest;
use App\Models\Category;
use App\Models\LeaveEntitlement;
use App\Models\LeavePolicy;
use Mockery\Undefined;

class LeavePolicyService
{
    public function store(StoreRequest $request)
    {
        $leavePolicy = LeavePolicy::create($request->all());

        $entitlement = $request->entitlement;
        foreach ($entitlement as $item) {
            $arr = [
                'leave_policy_id' => $leavePolicy->id,
                'layer' => $item['layer'],
                'amount' => $item['amount'],
                'start_year_of_service' => $item['start_year_of_service'],
                'end_year_of_service' => $item['end_year_of_service'],
            ];
            LeaveEntitlement::create($arr);
        }
        // method to insert multiple/array of requests into db of related model
        $category = $request->category;
        foreach ($category as $item) {
            $arr = [
                'leave_policy_id' => $leavePolicy->id,
                'name' => $item['name'],
                'data' => $item['data'],
            ];
            Category::create($arr);
        }
        // method to insert single request into db of related model
        // $category->fill([
        //     'leave_policy_id' => $leavePolicy->id,
        //     'name' => $request->name,
        //     'data' => $request->data
        // ]);
        // $leavePolicy->category()->save($category);

        // to return every related models to leave policy
        $detailPolicy = LeavePolicy::whereId($leavePolicy->id)
            ->with(['leaveEntitlement', 'approvalConfig', 'category'])
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Leave policies stored successful',
            'leave_policy' => $detailPolicy
        ]);
    }

    public function update(LeavePolicy $leavePolicy, StoreRequest $request)
    {
        $leavePolicy->update($request->validated());

        $entitlement = $request->entitlement;
        foreach ($entitlement as $item) {
            if (isset($item['id'])) {
                LeaveEntitlement::where('id', $item['id'])
                    ->update([
                        'layer' => $item['layer'],
                        'amount' => $item['amount'],
                        'start_year_of_service' => $item['start_year_of_service'],
                        'end_year_of_service' => $item['end_year_of_service'],
                    ]);
            } else {
                $arr = [
                    'leave_policy_id' => $leavePolicy->id,
                    'layer' => $item['layer'],
                    'amount' => $item['amount'],
                    'start_year_of_service' => $item['start_year_of_service'],
                    'end_year_of_service' => $item['end_year_of_service'],
                ];
                LeaveEntitlement::create($arr);
            }
        }
        // method to insert multiple/array of requests into db of related model
        $category = $request->category;
        foreach ($category as $item) {
            // check if item existed, just update the new data
            if (isset($item['id'])) {
                Category::where('id', $item['id'])
                    ->update([
                        'leave_policy_id' => $leavePolicy->id,
                        'name' => $item['name'],
                        'data' => $item['data'],
                        'status' => $item['status'],
                    ]);
            } else { // check if item not exist, create and store new data
                $arr = [
                    'leave_policy_id' => $leavePolicy->id,
                    'name' => $item['name'],
                    'data' => $item['data'],
                ];
                Category::create($arr);
            }
        }

        // return leave policy including all related models
        $detailPolicy = LeavePolicy::whereId($leavePolicy->id)
            ->with(['leaveEntitlement', 'approvalConfig', 'category'])
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Leave policies updated successful',
            'leave_policy' => $detailPolicy
        ]);
    }
};
