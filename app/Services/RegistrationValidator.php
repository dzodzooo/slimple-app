<?php
declare(strict_types=1);
namespace App\Services;

use App\Exception\ValidationException;
use Rakit\Validation\Validator;

class RegistrationValidator
{
    public function validate(array $userData)
    {
        $validator = new Validator();

        $validation = $validator->make($userData, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|regex:/[a-zA-Z]*[\d][a-zA-Z]*/',
            'confirmPassword' => 'required|same:password'
        ]);

        $validation->setAlias('confirmPassword', 'Confirm Password');
        $validation->setMessage('regex', 'The :regex must contain at least one number.');
        $validation->validate();

        if ($validation->fails()) {
            $errors = $validation->errors();
            $oldData = array_diff_key($userData, ['password' => '', 'confirmPassword' => '']);
            throw new ValidationException($errors->toArray(), $oldData);
        }
    }

}