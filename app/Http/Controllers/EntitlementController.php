<?php

namespace App\Http\Controllers;

use App\Http\Resources\EntitlementResource;
use App\Models\Entitlement;
use Illuminate\Http\Request;

class EntitlementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entitlements = Entitlement::all();
        return response([
            'entitlement' =>
            EntitlementResource::collection($entitlements),
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
        $entitlements = Entitlement::where('employee_id', $id)
            ->with(['leavePolicy' => function ($query) {
                $query->select('id', 'name', 'color', 'abbreviation', 'description');
            }])
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Retrieve employee entitlements successful',
            'entitlement' => $entitlements
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
