<?php
declare(strict_types=1);
namespace App\Contracts;

interface TransactionRepositoryInterface
{
    public function create(array $transactionData);
    public function getAll();
    public function update(array $transactionData);
    public function delete(int $id);
}