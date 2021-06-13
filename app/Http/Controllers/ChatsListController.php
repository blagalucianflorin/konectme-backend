<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Chat;
use Illuminate\Support\Facades\DB;

class ChatsListController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  Request $request
     * @param  $id
     * @return string
     */
    public function show (Request $request, $id): string
    {
        $allChats = Chat::all ();
        $retChats = array();

        foreach ($allChats as $chat)
        {
            if (in_array ($id, json_decode ($chat -> users)))
                array_push ($retChats, $chat);
        }

        return (json_encode ([
            'success'  => true,
            'chats'    => $retChats
        ]));
    }
}
