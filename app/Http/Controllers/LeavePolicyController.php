<?php

namespace App\Http\Controllers;

use App\Enums\LeavePolicyType;
use App\Http\Requests\LeavePolicy\LeavePolicyPostRequest;
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
    public function index()
    {
        $withoutEntitlement = LeavePolicy::where('type', LeavePolicyType::WithoutEntitlement)->get();
        $withEntitlement = LeavePolicy::where('type', LeavePolicyType::WithEntitlement)->get();
        $leaveCredit = LeavePolicy::where('type', LeavePolicyType::LeaveCredit)->get();

        $result = [
            'with_entitlement' => $withEntitlement,
            'without_entitlement' => $withoutEntitlement,
            'leave_credit' => $leaveCredit,
        ];

        return $this->sendResponse("Index leave policies successful", $result, 200);
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
    public function store(LeavePolicyPostRequest $request, LeavePolicyService $service)
    {
        $result = $service->store($request);

        return $this->sendResponse("Store leave policies successful", $result, 200);
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
            ->with('leavePolicyEntitlement', 'leaveCategory')
            ->get();

        return $this->sendResponse("Show leave policies successful", $leavePolicy, 200);
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
     * @param  \App\Http\Requests\LeavePolicy\LeavePolicyPostRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LeavePolicy $leavePolicy, LeavePolicyPostRequest $request, LeavePolicyService $service)
    {
        $result = $service->update($leavePolicy, $request);

        return $this->sendResponse("Update leave policies successful", $result, 200);
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

        return $this->sendResponse("Delete leave policies successful", $leavePolicy, 200);
    }
}
