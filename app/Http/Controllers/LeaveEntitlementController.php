<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveEntitlement\StoreRequest;
use App\Http\Resources\LeaveEntitlementResource;
use App\Models\LeaveEntitlement;
use Illuminate\Http\Request;

class LeaveEntitlementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leaveEntitlement = LeaveEntitlement::all();

        return response([
            'success' => true,
            'message' => 'Retrieve index leave entitlement successful',
            'data' =>
            LeaveEntitlementResource::collection($leaveEntitlement)
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
        $leaveEntitlement = LeaveEntitlement::create($request->all());

        return response([
            'success' => true,
            'message' => 'Leave Entitlement Store Successful',
            'data' => new
                LeaveEntitlementResource($leaveEntitlement),
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
