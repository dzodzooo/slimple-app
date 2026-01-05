<?php
declare(strict_types=1);
namespace App\Services;

use App\Entity\Category;
use App\Entity\Transaction;
use App\Entity\User;
use DateTime;

class TransactionFactory
{
    public static function create(array $transactionData, User $user, Category $category): Transaction
    {
        $transaction = new Transaction();
        $transaction->setAmount((float) $transactionData['amount']);
        $transaction->setDescription($transactionData['description']);
        $transaction->setDate(new DateTime($transactionData['date']));
        $transaction->setUser($user);
        $transaction->setCategory($category);
        return $transaction;
    }
}