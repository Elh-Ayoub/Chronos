<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;

class ApiEventController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return (Event::find($id))  ? Event::find($id) : (["fail" => "Event not found"]);
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'max:500',
            'start' => 'required|string',
            'backgroundColor' =>'required|string',
            'borderColor' =>'required|string',
        ]);
        $event = Event::find($id);
        if(!$event){
            return ['fail' => 'Event not found!'];
        }
        if($validator->fails()){
            return ['fail-arr' => ($validator->errors()->toJson())];
        }
        $event->update(array_merge($request->all(), [
            'start' => date('D M d Y H:i:s', strtotime($request->start)),
            'end' => ($request->end) ? date('D M d Y H:i:s', strtotime($request->end)) : (null),
            'allDay' => ($request->allDay  == 'true') ? ('true') : (null)]));
        return ['success' => 'Event Updated successfully!'];
    }
}
