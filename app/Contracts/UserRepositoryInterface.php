<?php
declare(strict_types=1);
namespace App\Contracts;

use App\DataObject\UserDTO;
use App\Entity\User;

interface UserRepositoryInterface
{
    public function getByEmail(string $email): User|null;
    public function create(array $userData): UserDTO|null;
    public function get(int $id): User|null;
    public function login(array $userData): array|null;
}