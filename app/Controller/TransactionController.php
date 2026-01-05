<?php
declare(strict_types=1);
namespace App\Controller;

use App\Contracts\CategoryRepositoryInterface;
use App\Contracts\TransactionRepositoryInterface;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TransactionController
{
    public function __construct(
        private readonly \Twig\Environment $twig,
        private readonly TransactionRepositoryInterface $transactionRepository,
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {

    }
    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $transactions = $this->transactionRepository->getAllTransactions();
        $categories = $this->categoryRepository->getAllCategories();
        $this->twig->addGlobal('transactions', $transactions);
        $this->twig->addGlobal('categories', $categories);
        $response->getBody()->write($this->twig->render('transactions.html.twig', []));
        return $response;
    }
    public function post(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $transactionData = $request->getParsedBody();
        $this->transactionRepository->addNewTransaction($transactionData);
        return $response->withHeader('Location', '/transactions')->withStatus(StatusCodeInterface::STATUS_FOUND);
    }
}