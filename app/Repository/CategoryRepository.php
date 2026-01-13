<?php
declare(strict_types=1);
namespace App\Repository;

use App\Contracts\CategoryRepositoryInterface;
use App\Contracts\SessionInterface;
use \App\Entity\Category;
use \App\Entity\User;
use App\Factory\CategoryFactory;
use Doctrine\ORM\EntityManager;

class CategoryRepository implements CategoryRepositoryInterface
{
    private $repository;
    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly SessionInterface $session
    ) {
        $this->repository = $this->entityManager->getRepository(Category::class);
    }
    public function create(array $categoryData)
    {
        $user = $this->entityManager->getReference(User::class, $this->session->get('user')->id);
        $this->entityManager->persist(CategoryFactory::create($categoryData, $user));
        $this->entityManager->flush();
    }
    public function get(int $id): Category|null
    {
        return $this->repository->find($id);
    }
    public function getAll(?array $orderby = ['id' => 'ASC'])
    {
        if (!$this->session->hasStarted() or !$this->session->get('user'))
            return [];
        $user = $this->entityManager->getReference(User::class, $this->session->get('user')->id);
        $categories = $this->repository->findBy(['user' => $user], $orderby);
        return $categories;
    }
    public function update(array $categoryData): bool
    {
        $category = $this->entityManager->find(Category::class, $categoryData['id']);
        if ($category === null) {
            return false;
        }
        $category->setName($categoryData['name']);
        $this->entityManager->persist($category);
        $this->entityManager->flush();
        return true;
    }

    public function delete(int $categoryId): bool
    {
        $category = $this->entityManager->find(Category::class, $categoryId);
        if (!$category) {
            return false;
        }
        $this->entityManager->remove($category);
        $this->entityManager->flush();
        return true;
    }
    public function getByName(string $name): Category|null
    {
        return $this->entityManager->getRepository(Category::class)->findOneBy(['name' => $name]);
    }
}