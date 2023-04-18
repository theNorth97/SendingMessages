<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 1000000; $i++) {
            $email = 'user' . $i . '@example.com';
            $userId = DB::table('users')->insertGetId([
                'username' => 'user' . $i,
                'email' => $email,
                'validts' => time() + 30 * 24 * 3600,
                'confirmed' => rand(0, 1),
            ]);
            DB::table('emails')->insert([
                'email' => $email,
                'user_id' => $userId,
                'checked' => false,
                'valid' => false,
            ]);
        }
    }
}
//  php artisan db:seed --class=UsersTableSeeder запустить сид
//  ps aux | grep artisan  узнать ID artisan процесса
//  kill ID процесса  остановить процесс создания пользователей

