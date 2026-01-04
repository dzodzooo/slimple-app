<?php
declare(strict_types=1);
namespace App\Repository;

use App\Contracts\TransactionRepositoryInterface;
use App\Entity\Category;
use App\Entity\Transaction;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManager;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }
    public function getAllTransactions()
    {
        if (!isset($_SESSION) or !isset($_SESSION['user'])) {
            return [];
        }
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find($_SESSION['user']->getId());
        $transactionRepostitory = $this->entityManager->getRepository(Transaction::class);

        $transactions = $transactionRepostitory->findBy(['user' => $user]);

        return $transactions;
    }
    public function addNewTransaction(array $transactionData)
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find($_SESSION['user']->getId());
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $category = $categoryRepository->find($transactionData['category']);
        $transaction = new Transaction();
        $transaction->setAmount((float) $transactionData['amount']);
        $transaction->setDescription($transactionData['description']);
        $transaction->setDate(new DateTime($transactionData['date']));
        $transaction->setUser($user);
        $transaction->setCategory($category);
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }
}