<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index()
    {
        $messages = Message::all();

        return (json_encode ($messages));
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
        $message = Message::find($id);

        if ($message == null)
            return (json_encode (false));

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
     * @return
     */
    public function update(Request $request, $id)
    {
        $message = Message::find ($id);

        if ($message == null)
            return (json_encode (false));

        $newContent         = ($request -> all())['content'];
        $message['content'] = $newContent;

        $message -> save();

        return (json_encode (true));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return
     */
    public function destroy($id)
    {
        $message = Message::find($id);

        if ($message == null)
            return (json_encode (false));

        $message -> delete();

        return (json_encode (true));
    }
}
