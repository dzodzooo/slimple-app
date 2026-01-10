<?php
declare(strict_types=1);
namespace App\Controller;

use App\Contracts\CategoryRepositoryInterface;
use App\Contracts\TransactionRepositoryInterface;
use App\Exception\InvalidFileUploadException;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use \App\Services\TransactionService;

class TransactionController
{
    public function __construct(
        private readonly \Twig\Environment $twig,
        private readonly TransactionService $transactionService
    ) {

    }
    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        [$transactions, $categories] = $this->transactionService->getAllTransactionsAndCategories();
        $this->twig->addGlobal('transactions', $transactions);
        $this->twig->addGlobal('categories', $categories);
        $response->getBody()->write($this->twig->render('transaction/transactions.html.twig', []));
        return $response;
    }
    public function post(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $transactionData = $request->getParsedBody();
        $this->transactionService->create($transactionData);
        return $response->withHeader('Location', '/transactions')->withStatus(StatusCodeInterface::STATUS_FOUND);
    }
    public function update(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $transactionData = $request->getParsedBody()['transaction'];
        $this->transactionService->update($transactionData);
        return $response->withStatus(StatusCodeInterface::STATUS_OK);
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $transactionId = (int) $request->getAttribute('id');
        $this->transactionService->delete($transactionId);
        return $response->withHeader('Location', '/transactions')->withStatus(StatusCodeInterface::STATUS_FOUND);
    }
    public function upload(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($this->transactionService->uploadFileFromRequest($request)) {
            return $response->withStatus(StatusCodeInterface::STATUS_FOUND)->withHeader('Location', '/transactions');
        }
        throw new InvalidFileUploadException('Invalid file.');
    }

}