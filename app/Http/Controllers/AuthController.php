<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Str;

class AuthController extends Controller
{
    public function login (Request $request)
    {
        $data = $request->all ();

        $validator = Validator::make ($data, [
            'username'   => 'required|max:55',
            'password'   => 'required|max:64'
        ]);

        if ($validator -> fails())
            return ($validator -> failed ());

        $user = User::where ('username', '=', $data['username']) -> first ();
        if (!$user)
        return (json_encode ([
            'token' => null,
            'error' => 'Wrong password or username'
        ]));

        if (password_verify ($data['password'], $user['password'])) {
            return (json_encode (['token' => $user['token']]));
        } else {
            return (json_encode ([
                'token' => null,
                'error' => 'Wrong password or username'
            ]));
        }
    }
}
