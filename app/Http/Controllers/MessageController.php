<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Chat;
use App\Models\User;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index ()
    {
        $messages = Message::all ();

        return (json_encode ($messages));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function store (Request $request)
    {
        $newMessage       = new Message;
        $reqContent       = $request -> all ();
        $verificationChat = Chat::findOrFail ($reqContent['chat_id']);
        $usersList        = json_decode ($verificationChat['users']);

        if($reqContent['type'] == null || $reqContent['content'] == null)
            return (json_encode([
                "succes"  => false,
                "message" => "No content"
            ]));

        if($reqContent['type'] == 'photo')
        {
            $id    = $reqContent['content'];
            $photo = Photo::find ($id);
            if($photo == null)
                return (json_encode([
                    "succes"  => false,
                    "message" => "Invalid photo"
                ]));
        }

        if (!in_array ($reqContent['sender_id'], $usersList))
            return (json_encode([
                "success" => false,
                "message" => "This user is not in chat."
            ]));

        $newMessage['sender_id'] = $reqContent['sender_id'];
        $newMessage['chat_id']   = $reqContent['chat_id'];
        $newMessage['content']   = $reqContent['content'];
        $newMessage['type']      = $reqContent['type'];
        $newMessage -> save ();

        $message = Message::find ($newMessage -> id);

        if ($reqContent['expiry_time'] != '0')
        {
            $newTime = Date ("Y-m-d H:i:s", strtotime (($reqContent['expiry_time'])." minutes",
                strtotime ($message['sent_at'])));
            $message -> expiry_time = $newTime;
        }
        else
        {
            $newTime = Date ("Y-m-d H:i:s", strtotime ("525600 minutes", strtotime ($message['sent_at'])));
            $message -> expiry_time = $newTime;
        }

        $message -> save ();

        return (json_encode([
            "success" => true,
            "message" => "Message was sent"
        ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return string
     */
    public function show ($id)
    {
        $message = Message::find ($id);
        if ($message == null)
            return (json_encode([
                "success" => false,
                "message" => "This message does not exist."
            ]));

        return (json_encode([
            'sender_id' => $message['sender_id'],
            'chat_id'   => $message['chat_id'],
            'sent_at'   => $message['sent_at'],
            'content'   => $message['content'],
            'type'      => $message['type']
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return string
     */
    public function update (Request $request, $id)
    {
        $message = Message::find ($id);
        if ($message == null)
            return (json_encode([
                "success" => false,
                "message" => "This message does not exist."
            ]));

        $newContent = ($request -> all())['content'];
        $newType    = ($request -> all())['type'];

        if($newType != $message['type'])
            return (json_encode([
                "success" => false,
                "message" => "Type of message can't be modified"
            ]));

        $token = $request -> bearerToken ();
        if($token == null)
            return (json_encode([
                "succes" => false,
                "message" => "User is not authenticated"
            ]));

        $user = DB::table('users') -> where ('token', $token) -> first ();
        if ($user == null)
            return (json_encode([
                "succes"  => false,
                "message" => "Token does not exist"
            ]));

        if($user -> id != $message['sender_id'])
            return (json_encode([
                "succes"  => false,
                "message" => "Unauthorized access"
            ]));

        $message['content'] = $newContent['content'];
        $message -> save ();

        return (json_encode([
            "success" => true,
            "message" => "Message was edited"
        ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @param  int  $id
     * @return string
     */
    public function destroy (Request $request, $id)
    {
        $message = Message::find ($id);
        if ($message == null)
            return (json_encode([
                "success" => false,
                "message" => "This message does not exist."
            ]));

        $token = $request -> bearerToken ();
        if($token == null)
            return (json_encode([
                "succes"  => false,
                "message" => "User is not authenticated"
            ]));

        $user = DB::table('users') -> where ('token', $token)-> first ();
        if ($user == null)
            return (json_encode([
                "succes"  => false,
                "message" => "Token does not exist"
            ]));


        if($user -> id != $message['sender_id'])
            return (json_encode([
                "succes"  => false,
                "message" => "Unauthorized access"
            ]));

        $message -> delete ();

        return (json_encode([
            "success" => true,
            "message" => "Message deleted"
        ]));
    }
}
