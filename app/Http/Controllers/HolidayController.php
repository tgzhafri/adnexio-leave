<?php

namespace App\Http\Controllers;

use App\Http\Requests\Holiday\StoreRequest;
use App\Http\Resources\HolidayResource;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $holidays = Holiday::all();
        return $this->sendResponse("Index holidays successful", $holidays, 200);
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
        $holidays = $request->holidays;

        if (!$holidays) { // update single holiday
            $singleHoliday = Holiday::create($request->all());
            return $this->sendResponse("Store holiday successful", $singleHoliday, 200);
        } else {
            foreach ($holidays as $item) { // update mass holidays
                $date = Carbon::createFromFormat('m/d/Y', $item['date'])->toDateString();
                $arr = [
                    'company_id' => 1,
                    'name' => $item['name'],
                    'date' => $date,
                    'day' => $item['week_day'],
                    'type' => $item['type'],
                    'holiday_type' => 'public',
                    'location' => $item['location'],
                ];
                Holiday::create($arr);
            }
        }

        $getHolidays = Holiday::all();
        return $this->sendResponse("Store holidays successful", $getHolidays, 200);
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
