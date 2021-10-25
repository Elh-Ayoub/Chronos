<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Chat;
use App\Models\Event;
use Illuminate\Http\Request;

class ChatController extends Controller
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
     * Display the specified resource.
     *
     * @param int $id -- (Event id) --
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event  = Event::find($id);
        $chat = Chat::where('event_id', $event->id)->first();
        if(!$event || !$chat){
            return back()->with('fail', 'Event or chat not found!');
        }
        $eventController = new EventController();
        $watchers = $eventController->getAllEventWatchers($event->id);
        return view('Chat.index', [
            'event' => $event, 
            'chat' => $chat, 
            'messages' => Message::where('chat_id', $chat->id)->get(),
            'participants' => $watchers,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        //
    }
}
