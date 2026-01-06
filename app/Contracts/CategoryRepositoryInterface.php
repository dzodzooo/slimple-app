<?php
declare(strict_types=1);

namespace App\Contracts;

interface CategoryRepositoryInterface
{
    public function create(array $categoryData);
    public function getAll();
    public function update(array $categoryData);
    public function delete(int $id);
}