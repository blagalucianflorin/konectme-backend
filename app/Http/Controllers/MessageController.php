<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Message::all();
        return (json_encode($messages));
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
        $newMessage = new Message;

        $newSender = ($request -> all())['sender_id'];
        $newMessage['sender_id'] = $newSender;

        $newChat = ($request -> all())['chat_id'];
        $newMessage['chat_id'] = $newChat;

        // $newSent = ($request->all())['sent_at'];
        // $newMessage['sent_at'] = $newSent;

        $newContent = ($request -> all())['content'];
        $newMessage['content'] = $newContent;

        $newMessage -> save();
        return (true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $message = Message::findOrFail($id);
        return json_encode(['sender_id' => $message['sender_id'], 
                            'chat_id' => $message['chat_id'],
                            'sent_at' => $message['sent_at'],
                            'content' => $message['content']]);
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
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        $newSender = ($request -> all())['sender_id'];
        $message['sender_id'] = $newSender;

        $newChat = ($request -> all())['chat_id'];
        $message['chat_id'] = $newChat;

        // $newSent = ($request->all())['sent_at'];
        // $message['sent_at'] = $newSent;

        $newContent = ($request -> all())['content'];
        $message['content'] = $newContent;

        $message -> save();
        return (true);
         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $message -> delete();
        return (true);
    }
}
