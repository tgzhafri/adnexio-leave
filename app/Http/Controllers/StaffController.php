<?php

namespace App\Http\Controllers;

use App\Http\Requests\Staff\StaffPostRequest;
use App\Http\Requests\Staff\StaffPutRequest;
use App\Http\Resources\StaffResource;
use App\Models\Staff;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staff = Staff::all();
        return response([
            'staff' =>
            StaffResource::collection($staff),
            'message' => 'Successful'
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
    public function store(StaffPostRequest $request)
    {
        $staff = Staff::create($request->all());

        return response([
            'staff' => new
                StaffResource($staff),
            'message' => 'Staff Store Success'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function show(Staff $staff)
    {
        return response(['staff' => new
            StaffResource($staff), 'message' => 'Show Success'], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(Staff $staff)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function update(Staff $staff, StaffPutRequest $request)
    {
        $staff->update($request->validated());

        // dd($staff);
        return response(['staff' => new
            StaffResource($staff), 'message' => 'Update Success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function destroy(Staff $staff)
    {
        $staff->delete();

        return response(['message' => 'Staff deleted']);
    }
}
