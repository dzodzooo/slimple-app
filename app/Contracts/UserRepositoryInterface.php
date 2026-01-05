<?php
declare(strict_types=1);
namespace App\Contracts;

use App\DataObject\UserDTO;

interface UserRepositoryInterface
{
    public function getByEmail(string $email): UserDTO|null;
    public function create(array $userData): UserDTO|null;
}