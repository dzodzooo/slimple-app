<?php
declare(strict_types=1);

namespace App\Contracts;

interface CategoryRepositoryInterface
{
    public function addNewCategory(array $categoryData);
    public function getAllCategories();
}