<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::create ('users', function (Blueprint $table)
        {
            $table -> id ();
            $table -> string ('email', 191) -> unique ();
            $table -> string ('password');
            $table -> string ('first_name');
            $table -> string ('last_name');
            $table -> string ('username') -> unique ();
            $table -> timestamp ('created_at') -> default (DB::raw ('CURRENT_TIMESTAMP'));
            $table -> foreignId ('photo_id') -> nullable ();
            $table -> string ('token', 255) -> unique ();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists ('users');
    }
}
