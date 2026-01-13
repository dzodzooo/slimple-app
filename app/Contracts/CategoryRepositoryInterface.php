<?php
declare(strict_types=1);

namespace App\Contracts;

use App\Entity\Category;

interface CategoryRepositoryInterface
{
    public function create(array $categoryData);
    public function get(int $id): Category|null;
    public function getByName(string $name): Category|null;
    public function getAll(?array $orderby = ['id' => 'ASC']);
    public function update(array $categoryData): bool;
    public function delete(int $id): bool;
}