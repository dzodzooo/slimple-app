<?php
declare(strict_types=1);
namespace App\Validator;

use App\Exception\ValidationException;
use Rakit\Validation\Validator;

class TransactionValidator
{
    public function validate(array $transactionData)
    {
        $validator = new Validator();
        $validation = $validator->make($transactionData, [
            'amount' => 'required|numeric|min:10',
            'description' => 'required|min:3|max:100',
            'date' => 'required|date'
        ]);

        $validation->validate();

        if ($validation->fails()) {
            throw new ValidationException($validation->errors->toArray(), $transactionData);
        }
    }
}