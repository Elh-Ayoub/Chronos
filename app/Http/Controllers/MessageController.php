<?php

namespace App\Http\Controllers;

use App\Events\Messages;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

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
        
        if(!$request->content && !$request->file('attachedFile')){
            return ['fail' =>  "Content or file are required!"];
        }
        $chat = Chat::find($id);
        if(!$chat){
            return ['fail' => 'Chat not found!'];
        }
        // to send simple msg
        if($request->content && $request->content != "null"){
            $message = Message::create([
                'author' => Auth::id(),
                'content' => $request->content,
                'chat_id' => $id,
            ]);
            event(new Messages($message, Auth::user()));
        }
        // to send file
        if($request->file('attachedFile') && $request->attachedFile != "null"){
            foreach($request->file('attachedFile') as $file){
                $url  = "";
                $fileName = str_replace(' ', '-', $file->getClientOriginalName());
                $ufile = $file->store('public');
                $ufile1 = $file->move(public_path('/chatrooms-files/' . $id), $fileName);
                $url = url('/chatrooms-files/' . $id. '/' . $fileName);
                $message = Message::create([
                    'author' => Auth::id(),
                    'content' => $url,
                    'chat_id' => $id,
                ]);
                event(new Messages($message, Auth::user()));  
            }
            
        }
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
            if (filter_var($message->content, FILTER_VALIDATE_URL)){
                $arr = explode('/', parse_url($message->content, PHP_URL_PATH));
                $filename = $arr[count($arr) - 1];
                File::delete(public_path('/chatrooms-files/' . $message->chat_id . '/'.$filename));
            }
            $message->delete();
            return ['success' => 'Message deleted successfully! '];
        }
    }
}
