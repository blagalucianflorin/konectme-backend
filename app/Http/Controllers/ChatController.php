<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index (): string
    {
        $chats = Chat::all ();

        return (json_encode ($chats));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return string
     */
    public function store (Request $request): string
    {
        $newChat  = new Chat;
        $newUsers = ($request -> all ())['users'];

//        foreach (json_decode ($newUsers) as $id)
//        {
//            $token       = $request -> bearerToken ();
//            $desiredUser = User::find ($id);
//            $user        = DB::table ('users') -> where ('token', $token) -> first ();
//
//            if($user == null)
//                return (json_encode ([
//                    "success"  => false,
//                    "message" => "Token does not exist"
//                ]));
//
//            if($desiredUser == null)
//                return (json_encode ([
//                    "success"  => false,
//                    "message" => "User does not exist"
//                ]));
//
//            if($user -> token != $desiredUser -> token)
//                    return (json_encode([
//                        "success"  => false,
//                        "message" => "Unauthorized access"
//                    ]));
//        }

        $newChat['users'] = $newUsers;
        $newName          = ($request -> all ())['name'];
        $newChat['name']  = $newName;
        $newChat -> save ();

        return (json_encode ([
            "success" => true,
            "message" => "Chat was created"
        ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  Request $request
     * @param  $id
     * @return string
     */
    public function show (Request $request, $id): string
    {
        $chat = Chat::find ($id);

        if ($chat == null)
            return (json_encode ([
                "succes"  => false,
                "message" => "This chat does not exist"
            ]));

        $messages      = DB::table ('messages') -> where ('chat_id', $id) -> get ();
        $cleanMessages = array ();
        foreach ($messages as $message)
        {
            array_push ($cleanMessages, $message);
        }

        // Check request is performed by a user in the chat
        $chatUsers = DB::table ('chats') -> where ('id', $id) -> first() -> users;
        $found     = false;
        foreach(json_decode ($chatUsers) as $userId)
        {
            $token       = $request -> bearerToken ();
            $desiredUser = User::find ($userId);
            $user        = DB::table ('users') -> where ('token', $token) -> first ();

            if($user == null)
                return (json_encode([
                    "succes"  => false,
                    "message" => "Token does not exist"
                ]));

            if($desiredUser == null)
                return (json_encode([
                    "succes"  => false,
                    "message" => "User does not exist"
                ]));

            if($user -> id == $userId)
            {
                $found = true;
                break;
            }
        }
        if ($found == false)
            return (json_encode ([
                "succes"  => false,
                "message" => "Unauthorized access"
            ]));

        return (json_encode ([
            'users'    => $chat['users'],
            'name'     => $chat['name'],
            'messages' => $cleanMessages
        ]));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  $id
     * @return string
     */
    public function update (Request $request, $id): string
    {
        $chat     = Chat::find ($id);
        $newUsers = ($request -> all ())['users'];

        foreach (json_decode ($newUsers) as $id)
        {
            $token       = $request -> bearerToken ();
            $desiredUser = User::find ($id);
            $user        = DB::table ('users') -> where ('token', $token) -> first ();

            if($user == null)
                return (json_encode([
                    "succes"  => false,
                    "message" => "Token does not exist"
                ]));

            if($desiredUser == null)
                return (json_encode ([
                    "succes"  => false,
                    "message" => "User does not exist"
                ]));

            if($user -> token != $desiredUser -> token)
                return (json_encode ([
                    "succes"  => false,
                    "message" => "Unauthorized access"
                ]));
        }

        $newName       = ($request -> all ())['name'];
        $chat['users'] = $newUsers;
        $chat -> save();

        if(count (json_decode ($newUsers)) >= 3)
            $chat['name'] = $newName;
        else
            return (json_encode ([
                    "success" => false,
                    "message" => "User does not exist."
            ]));
        $chat -> save ();

        return json_encode ([
            "success" => true,
            "message" => "Chat was edited"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @param  $id
     * @return string
     */
    public function destroy (Request $request, $id): string
    {
        $chat  = Chat::findOrFail ($id);
        $users = $chat['users'];

        foreach (json_decode ($users) as $id)
        {
            $token       = $request -> bearerToken ();
            $desiredUser = User:: find ($id);
            $user        = DB::table ('users') -> where ('token', $token) -> first ();

            if($user == null)
                    return (json_encode([
                        "succes" => false,
                        "message" => "Token does not exist"
                    ]));

            if($desiredUser == null)
                    return (json_encode([
                        "succes" => false,
                        "message" => "User does not exist"
                    ]));

            if($user -> token != $desiredUser -> token)
                unset ($id);

            $chat -> save ();
        }

        return (json_encode ([
            "success" => true,
            "message" => "None"
        ]));
    }
}
