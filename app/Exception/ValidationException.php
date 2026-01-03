<?php
declare(strict_types=1);
namespace App\Exception;

use Exception;

class ValidationException extends Exception
{

    public function __construct(public readonly array $errors, public readonly array $oldData)
    {
        parent::__construct(json_encode($errors));
    }
}