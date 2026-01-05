<?php
declare(strict_types=1);
namespace App\Contracts;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function getAll();
    public function getByEmail(string $email): User|null;
    public function create(array $userData): User|null;
}