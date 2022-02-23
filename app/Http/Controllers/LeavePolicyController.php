<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeavePolicy\StoreRequest;
use App\Http\Resources\LeavePolicyCollection;
use App\Models\LeavePolicy;
use App\Services\LeavePolicyService;
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

        // $leavePolicy = LeavePolicy::all();
        // return response([
        //     'success' => true,
        //     'message' => 'Retrieve Index leave policies successful',
        //     'leave_policy' => LeavePolicyResource::collection($leavePolicy),
        // ], 200);
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
    public function store(StoreRequest $request, LeavePolicyService $service)
    {
        return $service->store($request);
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
            ->with(['leaveEntitlement', 'approvalRoute', 'category'])
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Retrieve Show leave policies successful',
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
    public function update(LeavePolicy $leavePolicy, StoreRequest $request, LeavePolicyService $service)
    {
        return $service->update($leavePolicy, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeavePolicy $leavePolicy)
    {
        $leavePolicy->delete();

        return response(['message' => 'Leave policy deleted success']);
    }
}
