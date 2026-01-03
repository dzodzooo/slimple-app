<?php
declare(strict_types=1);
namespace App\Contracts;

use App\Entity\User;

interface AuthInterface
{
    public function userExists(array $userData): User|null;
    public function register(array $userData);
    public function login(array $userData);
}