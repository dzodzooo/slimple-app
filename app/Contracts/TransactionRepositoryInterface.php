<?php
declare(strict_types=1);
namespace App\Contracts;

interface TransactionRepositoryInterface
{
    public function getAllTransactions();
    public function addNewTransaction(array $transactionData);
}