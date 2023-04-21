<?php

namespace App\Services;

use App\Models\Email;
use App\Models\User;

class SubscriptionService
{
    /**
     * @var EmailValidator
     */
    protected EmailValidator $emailValidator;

    /**
     * @param EmailValidator $emailValidator
     */
    public function __construct(EmailValidator $emailValidator)
    {
        $this->emailValidator = $emailValidator;
    }

    /**
     * @param $email
     * @return bool
     */
    public function checkEmail($email): bool
    {
        return $this->emailValidator->checkEmail($email);
    }

    /**
     * @return void
     */
    public function sendSubscriptionEmails(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $email = $user->email;

            if (!$this->checkEmail($email)) {
                continue;
            }

            // Отправить электронное письмо подписчику
        }

        // ...
    }
}
