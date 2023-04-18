<?php

namespace App\Console\Commands;

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

                if (!$this->check_email($email)) {
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
                DB::table('users')->where('id', $user->id)->update(['notified_at' => now()]);
            }
        }

        $this->info('Check subscriptions command completed successfully.');
        return 0;
    }

    //Функция check_email($email)Проверяет емейл на валидность и возвращает true или false.
    //Пользователь должен быть изначально в таблице emails, со значениями checked - false , valid - false
    //? Функция работает от 1 секунды до 1 минуты.
    //? Вызов функции платный.

    public function check_email($email): bool
    {
        //проверяем, существует ли уже запись с таким email в таблице emails
        $existing_email = DB::table('emails')->where('email', $email)->first();

        //проверяем, является ли email валидным
        $valid = (bool)filter_var($email, FILTER_VALIDATE_EMAIL);

        if ($existing_email) {
            // сли запись существует, обновляем ее checked и valid значения
            DB::table('emails')->where('email', $email)->update([
                'checked' => true,
                'valid' => $valid,
            ]);
            return $valid;
        } else {
            //если запись отсутствует, создаем новую запись с checked=true и valid соответствующим результату проверки
            DB::table('emails')->insert([
                'email' => $email,
                'checked' => true,
                'valid' => $valid,
            ]);
            return $valid;
        }
    }
}
