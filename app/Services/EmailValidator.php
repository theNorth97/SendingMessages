<?php

namespace App\Services;

use App\Models\Email;

class EmailValidator
{
    public function checkEmail($email): bool
    {
        //проверяем, существует ли уже запись с таким email в таблице emails
        $emailModel = Email::where('email', $email)->first();

        //проверяем, является ли email валидным
        $valid = (bool)filter_var($email, FILTER_VALIDATE_EMAIL);

        if ($emailModel) {
            //если запись существует, обновляем ее checked и valid значения
            $emailModel->update([
                'checked' => true,
                'valid' => $valid,
            ]);

        } else {

            //если запись отсутствует, создаем новую запись с checked=true и valid соответствующим результату проверки
            $emailModel->create([
                'email' => $email,
                'checked' => true,
                'valid' => $valid,
            ]);
        }
        return $valid;
    }
}
