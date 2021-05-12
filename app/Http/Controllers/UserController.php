<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index ()
    {
        $users = User::all ();

        $retData = json_encode([
            "success" => true,
            "users"   => $users
        ]);

        return ($retData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return string
     */
    public function store (Request $request): string
    {
        $data = $request -> all ();

        $validator = Validator::make ($data, [
            'first_name' => 'required|max:55',
            'last_name'  => 'required|max:55',
            'username'   => 'required|max:55|unique:users',
            'email'      => 'email|required|unique:users',
            'password'   => 'required|max:64'
        ]);

        if ($validator -> fails())
            return (json_encode ([
                "success"   => false,
                "validator" => $validator -> failed ()
            ]));

        do {
            $token = Str::random (191);
        } while (User::where ("token", "=", $token) -> first () instanceof User);

        $newUser               = new User;
        $newUser['first_name'] = $data['first_name'];
        $newUser['last_name']  = $data['last_name'];
        $newUser['username']   = $data['username'];
        $newUser['email']      = $data['email'];
        $newUser['password']   = password_hash ($data['password'], PASSWORD_DEFAULT);
        $newUser['token']      = $token;

        $newUser -> save ();

        $retData = json_encode([
            "success" => true,
            "user"    => [
                "id"    => $newUser['id'],
                "token" => $newUser['token']
            ]
        ]);

        return ($retData);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param  $id
     * @return string
     */
    public function show (Request $request, $id): string
    {
        $token = $request -> bearerToken ();

        if ($token == null)
            return (json_encode ([
                "success" => false,
                "message" => "User is not logged in"
            ]));

        $desiredUser = User::find ($id);
        $user        = DB::table ('users') -> where ('token', $token) -> first ();

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

        if ($desiredUser == null)
            return (json_encode ([
                "success" => false,
                "message" => "User doesn't exist"
            ]));

        $retData = json_encode([
            "success" => true,
            "user"    => [
                "id"    => $desiredUser['id'],
                "token" => $desiredUser['token']
            ]
        ]);

        return ($retData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  $id
     * @return string
     */
    public function update (Request $request, $id): string
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

        if ($desiredUser == null)
            return (json_encode ([
                "success" => false,
                "message" => "User doesn't exist"
            ]));

        $validator = Validator::make ($requestData, [
//            'first_name' => 'required|max:55',
//            'last_name'  => 'required|max:55',
//            'username'   => 'required|max:55',
            'email'      => 'email|required',
            'password'   => 'required|max:64'
        ]);

        if ($validator -> fails())
            return (json_encode ([
                "success"   => false,
                "validator" => $validator -> failed ()
            ]));

//        $desiredUser['first_name'] = $requestData['first_name'];
//        $desiredUser['last_name']  = $requestData['last_name'];
//        $desiredUser['username']   = $requestData['username'];
        $user['email']           = $requestData['email'];
        $desiredUser['password'] = bcrypt ($requestData['password']);

        $desiredUser -> save ();

        $retData = json_encode([
            "success" => true,
            "message" => "User data updated"
        ]);

        return response($retData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return string
     */
    public function destroy (Request $request, $id): string
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

        if ($desiredUser == null)
            return (json_encode ([
                "success" => false,
                "message" => "User doesn't exist"
            ]));

        $desiredUser -> delete ();

        return (json_encode ([
            "success" => true,
            "message" => "User deleted"
        ]));
    }
}
