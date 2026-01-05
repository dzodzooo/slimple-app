<?php
declare(strict_types=1);
namespace App\Repository;

use App\Contracts\SessionInterface;
use App\Contracts\TransactionRepositoryInterface;
use App\Entity\Category;
use App\Entity\Transaction;
use App\Entity\User;
use App\Services\TransactionFactory;
use DateTime;
use Doctrine\ORM\EntityManager;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly SessionInterface $session
    ) {
    }
    public function getAllTransactions()
    {
        if (!$this->session->hasStarted()) {
            return [];
        }

        $userDTO = $this->session->get('user');
        if (!isset($userDTO)) {
            return [];
        }

        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find($userDTO->id);

        $transactionRepostitory = $this->entityManager->getRepository(Transaction::class);

        $transactions = $transactionRepostitory->findBy(['user' => $user]);

        return $transactions;
    }
    public function addNewTransaction(array $transactionData)
    {
        if (!$this->session->hasStarted()) {
            return [];
        }

        $userDTO = $this->session->get('user');
        if (!isset($userDTO)) {
            return [];
        }
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find($userDTO->id);

        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $category = $categoryRepository->find($transactionData['category']);


        $this->entityManager->persist(TransactionFactory::create($transactionData, $user, $category));
        $this->entityManager->flush();
    }
}