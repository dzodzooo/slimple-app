<?php
declare(strict_types=1);
namespace App\Factory;

use App\Entity\Category;
use App\Entity\User;

class CategoryFactory
{
    public static function create(array $categoryData, User $user): Category
    {
        $category = new Category();
        $category->setName($categoryData['name']);
        $category->setUser($user);
        return $category;
    }
}