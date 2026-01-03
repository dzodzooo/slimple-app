<?php
declare(strict_types=1);
namespace App\Services;

use App\Contracts\AuthInterface;
use App\Contracts\UserRepositoryInterface;
use App\Entity\User;

class AuthService implements AuthInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }
    public function register(array $userData)
    {
        $this->userRepository->create($userData);
    }

    public function userExists(array $userData): User|null
    {
        return $this->userRepository->getByEmail($userData['email']);
    }

    public function login(array $userData)
    {
        $user = $this->userRepository->getByEmail($userData['email']);
        if (!isset($user))
            return null;

        if (password_verify($userData['password'], $user->getPassword())) {
            return $user;
        }
        return null;
    }
}