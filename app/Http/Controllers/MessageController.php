<?php

namespace App\Http\Controllers;

use App\Events\Messages;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id -- chat id --
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);
        if($validator->fails()){
            return ['fail-arr' => ($validator->errors()->toJson())];
        }
        $chat = Chat::find($id);
        if(!$chat){
            return ['fail' => 'Chat not found!'];
        }
        $message = Message::create([
            'author' => Auth::id(),
            'content' => $request->content,
            'chat_id' => $id,
        ]);
        event(new Messages($message, Auth::user()));
        if(!$message){
            return ['fail' => 'Something went wrong!'];
        }
        return ['success' => 'Message sent successfully!'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
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
            'content' => 'required|string',
        ]);
        if($validator->fails()){
            return ['fail-arr' => ($validator->errors()->toJson())];
        }
        $message = Message::find($id);
        if(!$message){
            return ['fail' => 'Message requested not found!'];
        }
        $message->update([
            'content' => $request->content,
        ]);
        return ['success' => 'Message updated successfully!'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $message = Message::find($id);
        if(!$message){
            return ['fail' => 'Message requested not found!'];
        }else{
            $message->delete();
            return ['success' => 'Message deleted successfully!'];
        }
    }
}
