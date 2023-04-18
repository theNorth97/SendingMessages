<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    // СОЕДЕНИНИЕ ТАБЛИЦ ПО id
    public function up(): void
    {
        Schema::table('emails', function (Blueprint $table) {
            $user = DB::table('users')->where('email', 'user@example.com')->first();
            $email = DB::table('emails')->where('email', 'user@example.com')->first();
            if ($email) {
                DB::table('emails')
                    ->where('id', $email->id)
                    ->update(['user_id' => $user->id]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emails', function (Blueprint $table) {
            //
        });
    }
};
