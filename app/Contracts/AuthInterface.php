<?php
declare(strict_types=1);
namespace App\Contracts;

use App\DataObject\UserDTO;

interface AuthInterface
{
    public function userExists(array $userData): UserDTO|null;
    public function register(array $userData);
    public function login(array $userData);
}