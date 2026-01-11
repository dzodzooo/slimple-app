<?php
declare(strict_types=1);
namespace App\Validator;

use App\Exception\ValidationException;
use Rakit\Validation\Validator;

class CategoryValidator
{
    public function __construct()
    {
    }
    public function validate($categoryData)
    {
        $validator = new Validator();
        $validation = $validator->make($categoryData, ['name' => 'required|max:30']);
        $validation->validate();
        if ($validation->fails()) {
            throw new ValidationException($validation->errors->toArray(), $categoryData);
        }
    }
}