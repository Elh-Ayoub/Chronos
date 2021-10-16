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
        $myCalendars = Calendar::where('user_id', Auth::id())->get();
        $to_find = array();
        foreach(Sharing::where(['target' => 'calendar', 'shared_to_email' => Auth::user()->email, 'accepted' => 'yes'])->get() as $share){
            array_push($to_find, $share->target_id);
        }
        $sharedCal = Calendar::find($to_find);
        // $myCalendars = $myCalendars->merge($sharedCal);
        return view('Calendar.list', ['calendars' => $myCalendars, 'sharedCal' => $sharedCal]);
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
        if(!$cal){
            return redirect('/home')->with('fail', 'Calendar not found!');
        }
        $check4shared = Sharing::where(['target' => 'calendar', 'target_id' => $id,'shared_to_email' => Auth::user()->email, 'accepted' => 'yes'])->first();
        if(($cal && $cal->user_id == Auth::id()) || $check4shared){
            $watchers = $this->getCalWatchers($cal->id);
            $invited = $this->getCalInvited($cal->id);
            return view('home', ['calendar' =>  $cal, 'events' => Event::where('calendar_id', $cal->id)->get(), 'watchers' => $watchers, 'invited' => $invited]); 
        }else{
            return back()->with('fail', 'Calendar not exist or not yours');
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
        $watchers = $this->getCalWatchers($mainCal->id);
        $invited = $this->getCalInvited($mainCal->id);
        return view('home', ['calendar' => $mainCal,'events' => $events, 'watchers' => $watchers, 'invited' => $invited]);
    }

    public function getCalWatchers($id){
        $calendar = Calendar::find($id);
        if(!$calendar){return null;}
        $watchers = array(['user' => User::find($calendar->user_id), 'role' => 'admin']);
        $shared2 = Sharing::where(['target' => 'calendar', 'target_id' => $id, 'accepted' => 'yes'])->get();
        foreach($shared2 as $share){
            array_push($watchers, ['user' => User::where('email', $share->shared_to_email)->first(), 'role' => $share->shared_to_role]);
        }
        return $watchers;
    }
    public function getCalInvited($id){
        $calendar = Calendar::find($id);
        if(!$calendar){return null;}
        $invited = array();
        $shared2 = Sharing::where(['target' => 'calendar', 'target_id' => $id, 'accepted' => 'no'])->get();
        foreach($shared2 as $share){
            array_push($invited, ['email' => $share->shared_to_email, 'role' => $share->shared_to_role]);
        }
        return $invited;
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
            'name' => 'required|string|between:5,30',
            'description' => 'max:500',
        ]);
        if($validator->fails()){
            return back()->with('fail-arr', json_decode($validator->errors()->toJson()));
        }
        $calendar = Calendar::find($id);
        if(!$calendar){
            return back()->with('fail', 'Calendar not found!');
        }
        $calendar->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return back()->with('success', 'Calendar updated successfully!');
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
            Sharing::where(['target' => 'calendar', 'target_id' => $id])->delete();
            Event::where('calendar_id', $id)->delete();
            return redirect('/home');
        }else{
            return back()->with('fail', 'Calendar not exist or not yours');
        }
    }
}
