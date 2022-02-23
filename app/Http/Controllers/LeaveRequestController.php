<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveRequest\StoreRequest;
use App\Http\Resources\LeaveRequestResource;
use App\Models\Approval;
use App\Models\LeaveDate;
use App\Models\LeaveRequest;
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
        return response([
            'success' => true,
            'message' => 'Retrieve employee leave requests successful',
            'leave_request' =>
            LeaveRequestResource::collection($leaveRequests),
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
    public function store(StoreRequest $request, Approval $approval)
    {
        $leaveRequest = LeaveRequest::create($request->all());

        $leaveDate = $request->date;
        // dd($leaveRequest->id);
        foreach ($leaveDate as $item) {
            $arr = [
                'leave_request_id' => $leaveRequest->id,
                'date' => $item['date'],
                'time' => $item['time']
            ];
            // dd($arr);
            LeaveDate::create($arr);
        }

        // // method to insert single request into db of related model
        $approval->fill([
            'leave_policy_id' => $leaveRequest->id,
            'verifier_id' => $leaveRequest->employee_id,
        ]);
        $leaveRequest->approval()->save($approval);

        // to return every related models to leave request
        $detailRequest = LeaveRequest::whereId($leaveRequest->id)
            ->with(['leaveDate'])
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Leave request stored successful',
            'leave_policy' => $detailRequest
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leaveRequests = LeaveRequest::where('employee_id', $id)
            ->with(['leaveDate', 'approval'])
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Retrieve employee leave requests successful',
            'leave_request' => $leaveRequests
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
    public function update(LeaveRequest $leaveRequest, StoreRequest $request, $id)
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
