<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Str;

class AuthController extends Controller
{
    /**
     * Check request data against user entry in data base.
     *
     * Returns success and whole user if data matches and failure and error message otherwise
     *
     * @return string
     */
    public function login (Request $request)
    {
        $data = $request -> all ();

        $validator = Validator::make ($data, [
            'username' => 'required|max:55',
            'password' => 'required|max:64'
        ]);

        if ($validator -> fails ())
            return (json_encode ([
                'success' => false,
                'message' => 'Wrong password or username',
                'token'   => null
            ]));

        $user = User::where ('username', '=', $data['username']) -> first ();
        if (!$user)
            return (json_encode ([
                'success' => false,
                'message' => 'Wrong password or username',
                'token'   => null
            ]));

        if (password_verify ($data['password'], $user['password']))
        {
            return (json_encode ([
                'success' => true,
                'message' => "Successfully logged in",
                'user'   => $user
            ]));
        }
        else
        {
            return (json_encode ([
                'success' => false,
                'message' => 'Wrong password or username',
                'token'   => null
            ]));
        }
    }
}
