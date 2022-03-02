<?php

namespace App\Http\Controllers;

use App\Models\Workday;
use Illuminate\Http\Request;

class WorkdayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workday = Workday::all();

        return $this->sendResponse("Index workday succesful", $workday, 200);
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
    public function update(Workday $workday, Request $request)
    {
        $workdays = $request->data;
        foreach ($workdays as $item) {
            Workday::where('id', $item['id'])
                ->update([
                    'day' => $item['day'],
                    'type' => $item['type']
                ]);
        }
        $workdayList = Workday::where('company_id', $workday->company_id)->get();

        return $this->sendResponse("Update workday succesful", $workdayList, 200);
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
