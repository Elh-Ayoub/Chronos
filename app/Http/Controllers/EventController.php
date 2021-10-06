<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Calendar;
use App\Models\Sharing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $cal = Calendar::find($id);
        if(!$cal || $cal->user_id !== Auth::id()){
            return back()->with('fail', 'Calendar not exist or not yours');
        }else{
             return view('Events.create', ['calendar' => $cal]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'max:500',
            'start' => 'required|string',
            'backgroundColor' =>'required|string',
            'borderColor' =>'required|string',
        ]);
        if($validator->fails()){
            return back()->with('fail-arr', json_decode($validator->errors()->toJson()));
        }
        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'start' => date('D M d Y H:i:s', strtotime($request->start)),
            'end' => ($request->end) ? date('D M d Y H:i:s', strtotime($request->end)) : (null),
            'allDay' => $request->allDay  == 'true'? ('true') : (null),
            'category' => $request->category,
            'backgroundColor' => $request->backgroundColor,
            'borderColor' => $request->borderColor,
            'calendar_id' => $request->calendar_id,
            'user_id' => Auth::id(),
        ]);
        if($event){
            // $event->update(['url' => url(url('/calendars/'. $request->calendar_id .'/events/edit/'. $event->id))]);
            return back()->with('success', 'Event created successfully!');
        }else{
            
            return back()->with('fail', 'Something went wrong. Try again!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $cal_id Calendar id
     * * @param int $ev_id Event id, 
     * @return \Illuminate\Http\Response
     */
    public function edit($cal_id, $ev_id)
    {
        $cal = Calendar::find($cal_id);
        $check4sharing = Sharing::where(['target' => 'calendar', 'target_id' => $cal_id, 'shared_to_email' => Auth::user()->email, 'shared_to_role' => 'admin'])->first();
        if(($cal && $cal->user_id !== Auth::id()) || $check4sharing){
            return view('Events.edit', ['calendar' => $cal, 'event' => Event::find($ev_id)]);
        }else{
            return back()->with('fail', 'Calendar not exist or not yours');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
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
            return back()->with('fail', 'Event not found!');
        }
        if($validator->fails()){
            return back()->with('fail-arr', json_decode($validator->errors()->toJson()));
        }
        $event->update(array_merge($request->all(), [
            'start' => date('D M d Y H:i:s', strtotime($request->start)),
            'end' => ($request->end) ? date('D M d Y H:i:s', strtotime($request->end)) : (null),
            'allDay' => ($request->allDay  == 'true') ? ('true') : (null)]));
        return redirect('/calendars/'.$event->calendar_id)->with('success', 'Event Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::find($id);
        $cal = Calendar::find($event->calendar_id);
        $check4sharing = Sharing::where(['target' => 'calendar', 'target_id' => $cal->id, 'shared_to_email' => Auth::user()->email, 'shared_to_role' => 'admin'])->first();
        if(($event && $event->user_id == Auth::id()) || ($cal->user_id == Auth::id()) || $check4sharing){
            Event::destroy($id);
            return back()->with('success', 'Event deleted successfully!');
        }else{
            return back()->with('fail', 'Event not exist or not yours');
        }
    }
}
