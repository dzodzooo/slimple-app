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
    public function getAll()
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
    public function create(array $transactionData)
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
        $categories = $categoryRepository->findBy(['id' => $transactionData['category'], 'user' => $user]);
        $category = count($categories) == 1 ? $categories[0] : null;

        $this->entityManager->persist(TransactionFactory::create($transactionData, $user, $category));
        $this->entityManager->flush();
    }

    public function update(array $transactionData)
    {
        var_dump($transactionData);
        $transaction = $this->entityManager->find(Transaction::class, $transactionData['id']);

        $transaction->setAmount((float) $transactionData['amount']);
        $transaction->setDate(new DateTime($transactionData['date']));
        $transaction->setDescription($transactionData['description']);
        $category = $this->entityManager->find(Category::class, $transactionData['categoryId']);
        $transaction->setCategory($category);

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }

    public function delete(int $id)
    {
        $transaction = $this->entityManager->find(Transaction::class, $id);
        $this->entityManager->remove($transaction);
        $this->entityManager->flush();
    }
}