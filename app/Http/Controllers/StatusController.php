<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    public function show (Request $request, $id)
    {
        $desiredUser = User::find ($id);

        if ($desiredUser == null)
            return (json_encode ([
                "success" => false,
                "message" => "User doesn't exist"
            ]));

        return json_encode ([
            "success" => true,
            "message" => "Status retrieved",
            "status" => $desiredUser -> status
        ]);
    }

    public function update (Request $request, $id)
    {
        $token = $request -> bearerToken ();

        if ($token == null)
            return (json_encode ([
                "success" => false,
                "message" => "User is not logged in"
            ]));

        $desiredUser = User::find ($id);
        $user        = DB::table ('users') -> where ('token', $token) -> first ();
        $requestData = $request -> all ();

        if ($user == null)
            return (json_encode ([
                "success" => false,
                "message" => "Token doesn't exist"
            ]));

        if ($user -> token != $desiredUser -> token)
            return (json_encode ([
                "success" => false,
                "message" => "Unauthorized access"
            ]));

        $desiredUser -> status = $requestData['status'];
        $desiredUser -> save ();

        return json_encode ([
            "success" => true,
            "message" => "Status set"
        ]);
    }
}
