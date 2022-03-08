<?php

namespace App\Http\Controllers;

use App\Models\LeaveApproval;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $approvals = LeaveApproval::all();

        return response()->json([
            'success' => true,
            'message' => 'Retrieve list of approvals successful',
            'approvals' => $approvals // to include every related models to leave policy
        ]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $approvals = LeaveApproval::where('verifier_id', $id)
            ->with('leaveRequest')
            ->get();
        // dd($approvals);

        return response()->json([
            'success' => true,
            'message' => 'Retrieve list of approvals successful',
            'approvals' => $approvals // to include every related models to leave policy
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
