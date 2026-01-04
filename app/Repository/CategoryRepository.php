<?php
declare(strict_types=1);
namespace App\Repository;

use App\Contracts\CategoryRepositoryInterface;
use \App\Entity\Category;
use \App\Entity\User;
use Doctrine\ORM\EntityManager;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }
    public function addNewCategory(array $categoryData)
    {
        $category = new Category();
        $category->setName($categoryData['name']);
        $user = $this->entityManager->getReference(User::class, $_SESSION['user']->getId());
        $category->setUser($user);
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }
    public function getAllCategories()
    {
        $repository = $this->entityManager->getRepository(Category::class);
        if (!isset($_SESSION) or !isset($_SESSION['user']))
            return [];
        $user = $this->entityManager->getReference(User::class, $_SESSION['user']->getId());
        $categories = $repository->findBy(['user' => $user]);
        return $categories;
    }
}