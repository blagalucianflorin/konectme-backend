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
    public function index()
    {
        $chats = Chat::all();
        return (json_encode($chats));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function store(Request $request)
    {
        $newChat = new Chat;

        $newUsers = ($request -> all())['users'];

        foreach(json_decode($newUsers) as $id)
        {
            $token = $request -> bearerToken();
            $desiredUser = User:: find ($id); 
            $user = DB::table ('users') -> where ('token', $token) -> first();

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
                    return (json_encode([
                        "succes" => false,
                        "message" => "Unauthorized access"
                    ]));
        }

        $newChat['users'] = $newUsers;

        $newName = ($request -> all())['name'];
        $newChat['name'] = $newName;

        $newChat -> save();

        return json_encode([
            "success" => true,
            "message" => "Chat was created"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chat  $chat
     * @return string
     */
    public function show($id)
    {
        $chat = Chat::find($id);

        if($chat == null)
            return json_encode([
                "succes" => false,
                "message" => "This chat does not exist"
            ]);

        return json_encode([
            'users' => $chat['users'],
            'name' => $chat['name']
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chat  $chat
     * @return string
     */
    public function update(Request $request, $id)
    {
        $chat = Chat::find($id);

        $newUsers = ($request -> all())['users'];
        foreach(json_decode($newUsers) as $id)
        {
            $token = $request -> bearerToken();
            $desiredUser = User:: find ($id); 
            $user = DB::table ('users') -> where ('token', $token) -> first();

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
                    return (json_encode([
                        "succes" => false,
                        "message" => "Unauthorized access"
                    ]));
        }

        $chat['users'] = $newUsers;

        $chat -> save();

        $newName = ($request -> all())['name'];
       
        //dd(json_decode($newUsers));

        if(count(json_decode($newUsers)) >= 3)
            $chat['name'] = $newName;
        else return json_encode([
            "success" => false, 
            "message" => "User does not exist."
             ]);
       
        $chat -> save();

        return json_encode([
            "success" => true,
             "message" => "Chat was edited"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chat  $chat
     * @return string
     */
    public function destroy(Request $request, $id)
    {
        $chat = Chat::findOrFail($id);

        $users = $chat['users'];
        foreach(json_decode($users) as $id)
        {
            $token = $request -> bearerToken();
            $desiredUser = User:: find ($id); 
            $user = DB::table ('users') -> where ('token', $token) -> first();

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
                unset($id);

            $chat -> save();
        }
       
        return json_encode([
            "success" => true,
            "message" => "None"
        ]);
    }
}
