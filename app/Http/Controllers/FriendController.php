<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;

use App\Models\User;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index(Request $request)
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return string
     */
    public function show (Request $request): string
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

        $areFriends = Friend::where ('friend_one_id', '=', $friendOne -> id) ->
            where ('friend_two_id', '=', $friendTwo -> id)-> first () != null;
        $areFriends |= Friend::where ('friend_one_id', '=', $friendTwo -> id) ->
            where ('friend_two_id', '=', $friendOne -> id)-> first () != null;

        if ($areFriends)
            return (json_encode ([
                "success" => true,
                "message" => "Users are friends"
            ]));
        else
            return (json_encode ([
                "success" => true,
                "message" => "Users are not friends"
            ]));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function store(Request $request)
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
            "message" => "Friend request sent"
        ]));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Friend  $friend
     * @return string
     */
    public function destroy (Request $request)
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

        $friend = Friend::where ('friend_one_id', '=', $friendOne -> id) ->
                where ('friend_two_id', '=', $friendTwo -> id)-> first ();

        if ($friend == null)
            $friend = Friend::where ('friend_one_id', '=', $friendTwo -> id) ->
                where ('friend_two_id', '=', $friendOne -> id)-> first ();

        if ($friend == null)
            return (json_encode ([
                "success" => false,
                "message" => "Users aren't friends"
            ]));

        $friend -> delete ();

        return (json_encode ([
            "success" => true,
            "message" => "Users aren't friends anymore"
        ]));
    }
}
