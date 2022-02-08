<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeavePolicy\StoreRequest;
use App\Http\Resources\LeavePolicyResource;
use App\Models\ApprovalConfig;
use App\Models\LeaveEligibility;
use App\Models\LeaveEntitlement;
use App\Models\LeavePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeavePolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $leavePolicies = LeavePolicy::whereId(1)
        //     ->with(['leaveEligibility', 'leaveEntitlement', 'approvalConfig'])
        //     ->first();

        // return $this->jsonResponse(
        //     compact('leavePolicies'),
        //     '',
        //     200
        // );

        $leavePolicies = LeavePolicy::all();

        return response([
            'success' => true,
            'message' => 'Retrieve index leave policies successful',
            'data' =>
            LeavePolicyResource::collection($leavePolicies)
        ], 200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $leavePolicy = LeavePolicy::create($request->all());

        $leaveEligibility = new LeaveEligibility();
        $leaveEligibility->leave_policy_id = $leavePolicy->id;
        $leaveEligibility->amount = $request->amount;
        $leaveEligibility->period = $request->period;
        $leaveEligibility->save();

        // $leaveEntitlement = new LeaveEntitlement();
        // $leaveEntitlement->leave_policy_id = $leavePolicy->id;
        // $leaveEntitlement->name = $request->name;
        // $leaveEntitlement->amount = $request->amount;
        // $leaveEntitlement->start_year_of_service = $request->start_year_of_service;
        // $leaveEntitlement->end_year_of_service = $request->end_year_of_service;
        // $leaveEntitlement->save();

        return response([
            'success' => true,
            'message' => 'Leave Policy Store Successful',
            'data' => new
                LeavePolicyResource($leavePolicy),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
