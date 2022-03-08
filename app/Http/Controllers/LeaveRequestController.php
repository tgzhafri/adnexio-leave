<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveRequest\LeaveRequestPostRequest;
use App\Http\Resources\LeaveRequestResource;
use App\Models\LeaveApproval;
use App\Models\LeaveDate;
use App\Models\LeaveRequest;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leaveRequests = LeaveRequest::all();
        return $this->sendResponse("Index leave request successful", $leaveRequests, 200);
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
    public function store(Request $request, LeaveApproval $approval, LeaveRequestService $service)
    {
        $result = $service->store($request, $approval);

        if ($result['code'] == 200 && !$result['data'] == null) {
            return $this->sendResponse($result['message'], $result['data'], $result['code']);
        } else {
            return $this->sendError($result['message'], $result['data'], $result['code']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, LeaveDate $leaveDate)
    {
        $leaveRequest = LeaveRequest::where('staff_id', $id)->get();

        $result = LeaveRequestResource::collection($leaveRequest);

        return $this->sendResponse("Show staff's leave request successful", $result, 200);
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
    public function update(LeaveRequest $leaveRequest, LeaveRequestPostRequest $request, $id)
    {
        $leaveRequest->update($request->validated());
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
