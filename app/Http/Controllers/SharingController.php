<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\Sharing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\Mail;

class SharingController extends Controller
{

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function invite2event(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        $event = Event::find($id);
        if(!$event){
            return back()->with('fail', 'Event not found!');
        }
        if($validator->fails()){
            return back()->with('fail-arr', json_decode($validator->errors()->toJson()));
        }
        foreach($request->email as $email){   
            $sharedEvent =Sharing::where(['shared_by' => Auth::id(),'target' => 'event','target_id' => $id,'shared_to_email' => $email,'accepted' => 'no'])->first(); 
            if(!$sharedEvent){
                $sharedEvent = Sharing::create([
                    'shared_by' => Auth::id(),
                    'target' => 'event',
                    'target_id' => $id,
                    'shared_to_email' => $email,
                    'accepted' => 'no',
                ]); 
            }
            $data = array(
                'event' => $event,
                'user' => Auth::user(),
                'email'=> $email,
                'sharing_id' => $sharedEvent->id,
            );
            Mail::send('Emails.invitaion-mail',$data, function($message ) use($data) {
                $message->to($data['email'], 'Invitaion to event')->subject
                    ('Invitation');
                $message->from(env('MAIL_USERNAME'), Auth::user()->username);
            });
            
        }
        return back()->with('success', 'Invitaion sent successfully!');
    }


    public function addSharedEvent(Request $request, $id)
    {
        $share = Sharing::find($id);
        if(!$share){
            return redirect('/home')->with('fail', 'Invitation not found!');
        }
        $share->update(['accepted' => 'yes']);
        return redirect('/home')->with('success', 'Shared event added successfully to Main Calendar');
    }

    public function shareCalendar(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        $calendar = Calendar::find($id);
        if(!$calendar){
            return back()->with('fail', 'Calendar not found!');
        }
        if($validator->fails()){
            return back()->with('fail-arr', ($validator->errors()->toArray()));
        }
        for($i = 0; $i < count($request->email); $i++){
            $sharedCalendar = Sharing::where(['shared_by' => Auth::id(),'target' => 'calendar', 'target_id' => $id,'shared_to_email' => $request->email[$i]])->first();
            if(!$sharedCalendar){
                $sharedCalendar = Sharing::create([
                    'shared_by' => Auth::id(),
                    'target' => 'calendar',
                    'target_id' => $id,
                    'shared_to_email' => $request->email[$i],
                    'accepted' => 'no',
                    'shared_to_role' => $request->role[$i],
                ]); 
            }
            $data = array(
                'calendar' => $calendar,
                'user' => Auth::user(),
                'email'=> $request->email[$i],
                'sharing_id' => $sharedCalendar->id,
                'role' => $request->role[$i],
            );
            Mail::send('Emails.sharing-mail',$data, function($message ) use($data) {
                $message->to($data['email'], 'Sharing calendar')->subject
                    ('Sharing calendar');
                $message->from(env('MAIL_USERNAME'), Auth::user()->username);
            });
        }
        return back()->with('success', 'Calendar shared successfully!');
    }

    public function addSharedCalendar(Request $request, $id){
        $share = Sharing::find($id);
        if(!$share){
            return redirect('/home')->with('fail', 'Calendar/invitaion not found!');
        }
        $share->update(['accepted' => 'yes', 'shared_to_role' => $request->role]);
        return redirect('/home')->with('success', 'Shared Calendar added to the list');
    }

    public function updateRole(Request $request, $cal_id, $user_id){
        $validator = Validator::make($request->all(), [
            'role' => 'required',
        ]);
        $calendar = Calendar::find($cal_id);
        $user = User::find($user_id);
        if(!$calendar || !$user){
            return back()->with('fail', 'Calendar/User not found!');
        }
        if($validator->fails()){
            return back()->with('fail-arr', ($validator->errors()->toArray()));
        }
        $sharing = Sharing::where(['target' => 'calendar', 'target_id' => $cal_id, 'shared_to_email' => $user->email, 'accepted' => 'yes'])->first();
        if(!$sharing){
            return back()->with('fail', 'Cannot see that this calendar shared with you!');
        }else{
            $sharing->update(['shared_to_role' => $request->role]);
            return back()->with('success', $user->username . ' updated successfully to ' . $request->role);
        }
    }

    public function destroySharedEvent($id){
        $sharedEvent = Sharing::where(['target' => 'event', 'target_id' => $id, 'shared_to_email' => Auth::user()->email]);
        if($sharedEvent){
            $sharedEvent->delete();
            return back()->with('success', 'Shared event and invitation deleted successfully!');
        }else{
            return back()->with('fail', 'Shared event not found!');
        }
    }
    
    public function destroyInvitedUser($cal_id, $user_id){
        $calendar = Calendar::find($cal_id);
        $user = User::find($user_id);
        if(!$calendar || !$user){
            return back()->with('fail', 'Calendar/User not found!');
        }
        $sharing = Sharing::where(['target' => 'calendar', 'target_id' => $cal_id, 'shared_to_email' => $user->email, 'accepted' => 'yes'])->first();
        if(!$sharing){
            return back()->with('fail', 'Cannot see that this calendar shared with you!');
        }else{
            $sharing->delete();
            return back()->with('success', $user->username . ' removed successfully from this calendar!');
        }
    }
    
    public function destroyInvitation($cal_id, $email){
        $calendar = Calendar::find($cal_id);
        if(!$calendar){
            return back()->with('fail', 'Calendar not found!');
        }
        $sharing = Sharing::where(['target' => 'calendar', 'target_id' => $cal_id, 'shared_to_email' => $email, 'accepted' => 'no'])->first();
        if(!$sharing){
            return back()->with('fail', 'Cannot see that this email has been invited!');
        }else{
            $sharing->delete();
            return back()->with('success', 'Invitation canceled successfully!');
        }
    }
}
