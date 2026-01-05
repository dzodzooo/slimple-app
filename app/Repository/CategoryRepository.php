<?php
declare(strict_types=1);
namespace App\Repository;

use App\Contracts\CategoryRepositoryInterface;
use App\Contracts\SessionInterface;
use \App\Entity\Category;
use \App\Entity\User;
use App\Services\CategoryFactory;
use Doctrine\ORM\EntityManager;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly SessionInterface $session
    ) {
    }
    public function addNewCategory(array $categoryData)
    {
        $user = $this->entityManager->getReference(User::class, $this->session->get('user')->id);
        $this->entityManager->persist(CategoryFactory::create($categoryData, $user));
        $this->entityManager->flush();
    }
    public function getAllCategories()
    {
        $repository = $this->entityManager->getRepository(Category::class);
        if (!$this->session->hasStarted() or !$this->session->get('user'))
            return [];
        $user = $this->entityManager->getReference(User::class, $this->session->get('user')->id);
        $categories = $repository->findBy(['user' => $user]);
        return $categories;
    }
}