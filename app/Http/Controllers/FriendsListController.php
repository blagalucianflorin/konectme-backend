<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;

class FriendsListController extends Controller
{
    /**
     * Return a list of friends for a user
     *
     * @param Request $request
     * @param $id
     * @return string
     */
    public function show (Request $request, $id): string
    {
        $reqData = $request -> all ();
        $token   = $request -> bearerToken ();
        $user    = User::find ($id);

        if ($token == null)
            return (json_encode ([
                "success" => false,
                "message" => "Unauthorized access"
            ]));

        $userCheck = User::where ('token', '=', $token) -> first ();
        if (($user == null) || ($userCheck == null) || ($userCheck -> id != $user -> id))
            return (json_encode ([
                "success" => false,
                "message" => "Unauthorized access"
            ]));

        $relationships = Friend::where ('friend_one_id', $user -> id) -> orWhere ('friend_two_id', $user -> id)
            -> get ();
        $friends = array ();
        foreach ($relationships as $relationship)
        {
            if ($relationship -> friend_one_id != $user -> id)
            {
                $friend = User::find ($relationship -> friend_one_id);
            }
            else
            {
                $friend = User::find ($relationship -> friend_two_id);
            }
            if ($relationship -> accepted == true)
                array_push ($friends, $friend);
        }

        return (json_encode ([
            "success" => true,
            "message" => "Friend list provided",
            "friends" => $friends
        ]));
    }
}
