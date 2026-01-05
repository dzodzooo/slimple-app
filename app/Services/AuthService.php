<?php
declare(strict_types=1);
namespace App\Services;

use App\Contracts\AuthInterface;
use App\Contracts\UserRepositoryInterface;
use App\DataObject\UserDTO;
class AuthService implements AuthInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }
    public function register(array $userData)
    {
        return $this->userRepository->create($userData);
    }

    public function userExists(array $userData): UserDTO|null
    {
        return $this->userRepository->getByEmail($userData['email']);
    }

    public function login(array $userData)
    {
        return $this->userRepository->login($userData);
        ;
    }
}