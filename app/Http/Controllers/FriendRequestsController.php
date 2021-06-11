<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use App\Models\Chat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class FriendRequestsController extends Controller
{
    public function store (Request $request)
    {
        $reqData = $request -> all ();
        $token   = $request -> bearerToken ();

        if ($token == null)
            return (json_encode ([
                "success" => false,
                "message" => "Unauthorized access"
            ]));

        $friendOne      = User::find ($reqData['friend_one']);
        $friendTwo      = User::find ($reqData['friend_two']);
        $friendOneCheck = User::where ('token', '=', $token) -> first ();

        if ($friendOne == null || $friendTwo == null)
            return (json_encode ([
                "success" => false,
                "message" => "User does not exist"
            ]));

        if ($friendOneCheck == null)
            return (json_encode ([
                "success" => false,
                "message" => "Invalid token"
            ]));

        if ($friendOneCheck -> id != $friendOne -> id)
            return (json_encode ([
                "success" => false,
                "message" => "Unauthorized access"
            ]));

        $alreadyFriends = Friend::where ('friend_one_id', '=', $friendOne -> id) ->
            where ('friend_two_id', '=', $friendTwo -> id)-> first () != null;
        $alreadyFriends |= Friend::where ('friend_one_id', '=', $friendTwo -> id) ->
            where ('friend_two_id', '=', $friendOne -> id)-> first () != null;

        if ($alreadyFriends)
            return (json_encode ([
                "success" => false,
                "message" => "Users are already friends"
            ]));

        $newFriend = new Friend;

        $newFriend -> friend_one_id = $reqData['friend_one'];
        $newFriend -> friend_two_id = $reqData['friend_two'];

        $newFriend -> save ();

        return (json_encode ([
            "success" => true,
            "message" => "Friend requst sent"
        ]));
    }

    public function show (Request $request, $id)
    {
//        $reqData = $request -> all ();
        $token   = $request -> bearerToken ();

        if ($token == null)
            return (json_encode ([
                "success" => false,
                "message" => "Unauthorized access"
            ]));

        $friendOneCheck = User::where ('id', '=', $id) -> first ();

        if ($friendOneCheck == null)
            return (json_encode ([
                "success" => false,
                "message" => "Invalid token"
            ]));

        if ($friendOneCheck -> token != $token)
            return (json_encode ([
                "success" => false,
                "message" => "Unauthorized access"
            ]));

        $relationship = Friend::where ('friend_two_id', $id) -> where ('accepted', false) -> get ();

        return (json_encode ([
            "success" => true,
            "message" => "List provided",
            "friend_requests" => $relationship
        ]));
    }

    // Accept friend request
    public function update (Request $request, $id)
    {
        $token   = $request -> bearerToken ();

        if ($token == null)
            return (json_encode ([
                "success" => false,
                "message" => "Unauthorized access"
            ]));

        $relationship   = Friend::where ('id', $id) -> first ();
        if ($relationship == null)
            return (json_encode ([
                "success" => false,
                "message" => "Friend request not sent"
            ]));

        $friendOneCheck = User::where ('id', '=', $relationship -> friend_two_id) -> first ();

        if ($friendOneCheck == null)
            return (json_encode ([
                "success" => false,
                "message" => "Invalid token"
            ]));

        if ($friendOneCheck -> token != $token)
            return (json_encode ([
                "success" => false,
                "message" => "Unauthorized access"
            ]));

        

        $newChat = new Chat;
        $newChat->users = json_encode(array($relationship -> friend_one_id, $relationship -> friend_two_id));
        $newChat->name = "Default";
        $newChat->save();

        $relationship -> accepted = true;
        $relationship -> save ();

        return (json_encode ([
            "success" => true,
            "message" => "Users are now friends"
        ]));
    }
}
