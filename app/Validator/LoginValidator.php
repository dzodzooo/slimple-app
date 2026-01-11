<?php
declare(strict_types=1);
namespace App\Validator;

use App\Exception\ValidationException;
use Rakit\Validation\Validator;

class LoginValidator
{
    public function validate(array $userData)
    {
        $validator = new Validator();

        $validation = $validator->make($userData, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $errors = $validation->errors();
            $oldData = ['email' => $userData['email']];
            throw new ValidationException($errors->toArray(), $oldData);
        }
    }

}