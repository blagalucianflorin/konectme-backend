<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Chat;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return
     */
    public function store(Request $request)
    {
        $newMessage       = new Message;
        $reqContent       = $request -> all ();
        $verificationChat = Chat::findOrFail ($reqContent['chat_id']);
        $usersList        = json_decode ($verificationChat['users']);

        if (!in_array ($reqContent['sender_id'], $usersList))
            return (json_encode (false));

        $newMessage['sender_id'] = $reqContent['sender_id'];
        $newMessage['chat_id']   = $reqContent['chat_id'];
        $newMessage['content']   = $reqContent['content'];

        $newMessage -> save();

        return (json_encode(true));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return
     */
    public function show($id)
    {
        $message = Message::findOrFail($id);

        return json_encode([
            'sender_id' => $message['sender_id'],
            'chat_id' => $message['chat_id'],
            'sent_at' => $message['sent_at'],
            'content' => $message['content']
        ]);
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
