<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::create ('friends', function (Blueprint $table)  
        {
            $table -> foreignId ("friend_one_id");
            $table -> foreignId ("friend_two_id");

            $table -> foreign ('friend_one_id') -> references ('id') -> on ('users');
            $table -> foreign ('friend_two_id') -> references ('id') -> on ('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists ('friends');
    }
}
