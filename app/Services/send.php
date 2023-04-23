<?php

namespace App\Services;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class send extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    // вынести handle в services

    public function handle()
    {
        //получаем список всех пользователей из таблицы users
        $users = DB::table('users')->get();

        //проходим по каждому пользователю и проверяем, если его подписка скоро истекает
        foreach ($users as $user) {
            $validts = $user->validts;
            $expirationDate = time() + 3 * 24 * 3600; // 3 дня в секундах
            if ($validts < $expirationDate && $user->confirmed) {
                //если подписка скоро истекает и пользователь подтвердил свою почту,
                //то отправить уведомление на электронную почту пользователя
                //проверить, есть ли email пользователя в таблице emails и является ли он проверенным и валидным
                $email = $user->email;

                if (!$this->checkEmail($email)) {
                    continue;
                }

                $username = $user->username;
                $data = [
                    'username' => $username,
                ];
                Mail::send('emails.subscription-expiring', $data, function ($message) use ($email) {
                    $message->to($email);
                    $message->subject('Your subscription is expiring soon');
                });

                //пометим пользователей, которым было отправлено уведомление
               $user->update(['notified_at' => now()]);
            }
        }

        $this->info('Check subscriptions command completed successfully.');
        return 0;
    }

}

