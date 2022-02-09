<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeavePolicy\StoreRequest;
use App\Http\Resources\LeavePolicyCollection;
use App\Http\Resources\LeavePolicyResource;
use App\Models\LeaveEntitlement;
use App\Models\LeavePolicy;
use Illuminate\Http\Request;

class LeavePolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LeavePolicy $leavePolicy)
    {
        return new LeavePolicyCollection($leavePolicy->get());
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
    public function store(StoreRequest $request, LeaveEntitlement $leaveEntitlement)
    {
        $leavePolicy = LeavePolicy::create($request->all());

        $leaveEntitlement->fill([
            'leave_policy_id' => $leavePolicy->id,
            'layer' => $request->layer,
            'amount' => $request->amount,
            'start_year_of_service' => $request->start_year_of_service,
            'end_year_of_service' => $request->end_year_of_service
        ]);

        $leavePolicy->leaveEntitlement()->save($leaveEntitlement);

        $detailPolicy = LeavePolicy::whereId($leavePolicy->id)
            ->with(['leaveEntitlement', 'approvalConfig'])
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Retrieve detail leave policies successful',
            'leave_policy' => $detailPolicy // to include every related models to leave policy
        ]);
        // $leaveEntitlement = new LeaveEntitlement();
        // $leaveEntitlement->leave_policy_id = $leavePolicy->id;
        // $leaveEntitlement->layer = $request->layer;
        // $leaveEntitlement->amount = $request->amount;
        // $leaveEntitlement->start_year_of_service = $request->start_year_of_service;
        // $leaveEntitlement->end_year_of_service = $request->end_year_of_service;
        // $leaveEntitlement->save();

        // return new LeavePolicyCollection($leavePolicy->latest()->limit(1)->get());
        // return response([
        //     'success' => true,
        //     'message' => 'Leave Policy Store Successful',
        //     'data' => new
        //         LeavePolicyResource($leavePolicy),
        // ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leavePolicy = LeavePolicy::whereId($id)
            ->with(['leaveEntitlement', 'approvalConfig'])
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Retrieve detail leave policies successful',
            'leave_policy' => $leavePolicy // to include every related models to leave policy
        ]);
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
