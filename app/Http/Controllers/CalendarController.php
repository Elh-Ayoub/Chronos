<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Sharing;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Calendar.list', ['calendars' => Calendar::where('user_id', Auth::id())->get()]);
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
            'name' => 'required|string|between:5,30',
            'description' => 'max:500',
        ]);
        if($validator->fails()){
            return back()->with('fail-arr', json_decode($validator->errors()->toJson()));
        }
        $calendar = Calendar::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);
        if($calendar){
            return back()->with('success', 'Calendar created successfully!');
        }else{
            return back()->with('fail', 'Something went wrong. Try again!');
        }
    }

    /**
     * Display the specified resource.
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cal = Calendar::find($id);
        if(!$cal || $cal->user_id !== Auth::id()){
            return back()->with('fail', 'Calendar not exist or not yours');
        }else{
            return view('home', ['calendar' =>  $cal, 'events' => Event::where(['user_id' => Auth::id(), 'calendar_id' => $cal->id])->get()]); 
        }
    }

    public function home() {
        $mainCal = Calendar::where(['user_id' => Auth::id(), 'name' => "Main Calendar"])->first();
        $to_find = array();
        foreach(Sharing::where(['target' => 'event', 'shared_to_email' => Auth::user()->email, 'accepted' => 'yes'])->get() as $share){
            array_push($to_find, $share->target_id);
        }
        $sharedEvents = Event::find($to_find);
        $events = Event::where(['user_id' => Auth::id(), 'calendar_id' => $mainCal->id])->get();
        $events = $events->merge($sharedEvents);
        return view('home', ['calendar' => $mainCal,'events' => $events]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Calendar $calendar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $calendar = Calendar::find($id);
        if($calendar && $calendar->user_id == Auth::id()){
            Calendar::destroy($id);
            return redirect('/home');
        }else{
            return back()->with('fail', 'Calendar not exist or not yours');
        }
    }
}
