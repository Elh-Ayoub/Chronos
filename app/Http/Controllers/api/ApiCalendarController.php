<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Event;
use Illuminate\Http\Request;

class ApiCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Calendar::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return (Calendar::find($id))  ? Calendar::find($id) : (["fail" => "Calendar not found"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showEvents($id)
    {
        return Event::where('calendar_id', $id)->get();
    }

}
