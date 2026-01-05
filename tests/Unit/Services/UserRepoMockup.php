<?php
declare(strict_types=1);
namespace Tests\Unit\Services;

use App\Contracts\UserRepositoryInterface;
use App\DataObject\UserDTO;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class UserRepoMockup implements UserRepositoryInterface
{
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
    public function init()
    {
        $userData = ['email' => 'user1@gmail.com', 'password' => 'user1password', 'name' => 'user1'];
        $this->create($userData);
        $userData = ['email' => 'user2@gmail.com', 'password' => 'user2password', 'name' => 'user2'];
        $this->create($userData);
    }
    public function getAll()
    {
        return $this->users;
    }

    public function getByEmail(string $email): UserDTO|null
    {
        $user = $this->users
            ->filter(static fn(User $user) => $user->getEmail() === $email)
            ->first();
        if (!$user)
            return null;
        return new UserDTO(-1, $user->getName(), $user->getEmail(), $user->getPassword());
    }

    public function create(array $userData): UserDTO|null
    {
        $user = new User();
        $user->setEmail($userData['email']);
        $user->setPassword(password_hash($userData['password'], PASSWORD_BCRYPT));
        $user->setName($userData['name']);
        $this->users->add($user);
        return new UserDTO(-1, $user->getName(), $user->getEmail(), $user->getPassword());
    }
}