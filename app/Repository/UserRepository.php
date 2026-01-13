<?php
declare(strict_types=1);
namespace App\Repository;

use App\Contracts\UserRepositoryInterface;
use App\DataObject\UserDTO;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }
    public function getByEmail(string $email): User|null
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if (isset($user)) {
            return $user;
        }
        return null;
    }

    public function create(array $userData): UserDTO|null
    {
        $user = new User();

        $user->setName($userData['name']);

        $user->setEmail($userData['email']);

        $password = password_hash($userData['password'], PASSWORD_BCRYPT);
        $user->setPassword($password);
        $user->setVerified(false);
        $this->entityManager->persist($user);

        $this->entityManager->flush();

        return new UserDTO($user->getId(), $user->getName(), $user->getEmail());
    }
    public function login(array $userData): array|null
    {
        $user = $this->getByEmail($userData['email']);

        if (isset($user) and password_verify($userData['password'], $user->getPassword())) {
            return [new UserDTO($user->getId(), $user->getName(), $user->getEmail()), $user->getVerified()];
        }

        return null;
    }
    public function get(int $id): User|null
    {
        return $this->entityManager->getRepository(User::class)->find($id);
    }
}
