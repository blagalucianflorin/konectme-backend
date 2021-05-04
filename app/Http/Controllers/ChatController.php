<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chats = Chat::all();
        return (json_encode($chats));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newChat = new Chat;

        $newUsers = ($request -> all())['users'];
        $newChat['users'] = $newChat;

        $newName = ($request -> all())['name'];
        $newChat['name'] = $newName;

        $newChat -> save();

        return (true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chat = Chat::findOrFail($id);
        return json_encode(['users' => $chat['users'], 
                            'name' => $chat['name']]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $chat = Chat::findOrFail($id);

        $newUsers = ($request -> all())['users'];
        $chat['users'] = $newUsers;

        $newName = ($request -> all())['name'];
        $chat['name'] = $newName;
        
        $chat -> save();
        return (true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $chat = Chat::findOrFail($id);
        $chat -> delete();
        return (true);
    }
}
