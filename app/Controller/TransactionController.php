<?php
declare(strict_types=1);
namespace App\Controller;

use App\Contracts\SessionInterface;
use App\Exception\InvalidFileUploadException;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \App\Services\TransactionService;
use App\Validator\TransactionValidator;

class TransactionController
{
    public function __construct(
        private readonly \Twig\Environment $twig,
        private readonly TransactionValidator $validator,
        private readonly TransactionService $transactionService,
        private readonly SessionInterface $session
    ) {

    }
    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        [$transactions, $categories] = $this->transactionService->getAllTransactionsAndCategories();
        $this->twig->addGlobal('transactions', $transactions);
        $this->twig->addGlobal('categories', $categories);
        $this->twig->addGlobal('verified', $this->session->get('verified'));
        $response->getBody()->write($this->twig->render('transaction/transactions.html.twig', [
            'oldData' => $this->session->get('oldData'),
            'errors' => $this->session->get('errors')
        ]));
        return $response;
    }
    public function post(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $transactionData = $request->getParsedBody();

        $this->validator->validate($transactionData);

        $this->transactionService->create($transactionData);

        $this->session->unset('errors');
        $this->session->unset('oldData');

        return $response->withHeader('Location', '/transactions')->withStatus(StatusCodeInterface::STATUS_FOUND);
    }
    public function update(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $transactionData = $request->getParsedBody()['transaction'];
        if ($this->transactionService->update($transactionData)) {
            var_dump($transactionData);
            return $response->withStatus(StatusCodeInterface::STATUS_OK);
        }
        return $response->withStatus(StatusCodeInterface::STATUS_BAD_REQUEST);
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