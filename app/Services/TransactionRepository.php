<?php
declare(strict_types=1);
namespace App\Services;

use App\Contracts\TransactionRepositoryInterface;
use App\Entity\Transaction;
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
        $qb = $this->entityManager->createQueryBuilder()
            ->select('t')
            ->from(Transaction::class, 't')
            ->where('t.user = :uid')
            ->setParameter('uid', $_SESSION['user']);

        $qb->orderBy('t.date', 'DESC')
            ->setMaxResults(5);

        $query = $qb->getQuery();

        $transactions = $query->getResult();
        //var_dump($transactions);
        return $transactions;
    }
    public function addNewTransaction(array $transactionData)
    {
        $transaction = new Transaction();
        $transaction->setAmount((float) $transactionData['amount']);
        $transaction->setDescription($transactionData['description']);
        $transaction->setDate(new DateTime($transactionData['date']));
        $transaction->setUser($_SESSION['user']);
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }
}