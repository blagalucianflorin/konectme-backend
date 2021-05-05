<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all ();

        return (json_encode ($users));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all ();

        $validator = Validator::make ($data, [
            'first_name' => 'required|max:55',
            'last_name'  => 'required|max:55',
            'username'   => 'required|max:55|unique:users',
            'email'      => 'email|required|unique:users',
            'password'   => 'required|max:64'
        ]);

        if ($validator -> fails())
            return ($validator -> failed ());

        do {
            $token = Str::random (255);
        } while (User::where("token", "=", $token)->first() instanceof User);

        $newUser               = new User;
        $newUser['first_name'] = $data['first_name'];
        $newUser['last_name']  = $data['last_name'];
        $newUser['username']   = $data['username'];
        $newUser['email']      = $data['email'];
        $newUser['password']   = password_hash ($data['password'], PASSWORD_DEFAULT);
        $newUser['token']      = $token;
        $newUser -> save ();

        return response([ 'user' => $newUser]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail ($id);

        return (json_encode ($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all ();

        $validator = Validator::make ($data, [
            'first_name' => 'required|max:55',
            'last_name'  => 'required|max:55',
            'username'   => 'required|max:55',
            'email'      => 'email|required',
            'password'   => 'required|max:64'
        ]);

        if ($validator -> fails())
            return ($validator -> failed ());

        $user               = User::findOrFail ($id);
        $user['first_name'] = $data['first_name'];
        $user['last_name']  = $data['last_name'];
        $user['username']   = $data['username'];
        // $user['email']      = $data['email'];
        $user['password']   = bcrypt ($data['password']);
        $user -> save ();

        return response([ 'user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user -> delete ();

        return (true);
    }
}
