<?php
declare(strict_types=1);
namespace App\Repository;

use App\Contracts\UserRepositoryInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    public function getAll()
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

    public function create(array $userData): void
    {
        $user = new User();

        $user->setName($userData['name']);

        $user->setEmail($userData['email']);

        $password = password_hash($userData['password'], PASSWORD_BCRYPT);
        $user->setPassword($password);

        $this->entityManager->persist($user);

        $this->entityManager->flush();
    }
}
