<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friend;
use Illuminate\Http\Request;


class FriendController extends Controller
{
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
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return string
     */
    public function destroy (Request $request, $id): string
    {
        $reqData = $request -> all ();
        $token   = $request -> bearerToken ();

        if ($token == null)
            return (json_encode ([
                "success" => false,
                "message" => "Unauthorized access"
            ]));

        $friendOne      = User::find ($id);
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
